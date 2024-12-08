<?php

namespace app\models;

use app\components\Cart;
use app\components\MicMailer;
use app\models\base\Purchase as BasePurchase;
use codesk\components\Html;
use Exception;
use Yii;
use yii\base\InvalidArgumentException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\UploadedFile;
use function mb_substr;

class Purchase extends BasePurchase {

    const TYPE_NOLOGIN = 0;
    const TYPE_REGISTER = 1;
    const TYPE_LOGIN = 2;
    const TYPE_MAGENTO = 3;
    const STATUS_NEW = 0;
    const STATUS_TRANSFER_CHECK = 1;
    const STATUS_PAID = 2;
    const STATUS_DELIVERIED = 3;
    const STATUS_CANCELLED = 4;
    const METHOD_TRANSFER = 0;
    const METHOD_CREDITCARD = 1;
    const METHOD_COUNTERSERVICE = 2;
    const METHOD_MAGENTO = 3;
    const DELIVERY_FREE = 1;
    const DELIVERY_REGISTER = 2;
    const DELIVERY_EMS = 3;
    const DELIVERY_ALPHA = 4;
    const DELIVERY_AIRMAIL = 5;
    const DELIVERY_CUSTOM = 6;
    const DELIVERY_STD = 7;

    public $transferFile;
    public $search;
    public $step = 1;
    public $checkPromotion;

    public static function find() {
        return Yii::createObject(PurchaseQuery::className(), [get_called_class()]);
    }

    public static function getStepValidation($code = null) {
        $ret = [
            1 => 'checkout-member',
            2 => 'checkout-invoice',
            3 => 'checkout-delivery',
            4 => 'checkout-method',
            5 => 'checkout-payment',
            6 => 'checkout-final',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getPurchaseTypeCartOptions($code = null) {
        $ret = [
            self::TYPE_NOLOGIN => 'สั่งซื้อโดยไม่ต้องลงทะเบียน',
            self::TYPE_REGISTER => 'ลงทะเบียนใหม่',
            self::TYPE_LOGIN => 'เข้าสู่ระบบ',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getPurchaseTypeOptions($code = null) {
        $ret = [
            self::TYPE_NOLOGIN => 'สั่งซื้อโดยไม่ต้องลงทะเบียน',
            self::TYPE_REGISTER => 'ลงทะเบียนใหม่',
            self::TYPE_LOGIN => 'เข้าสู่ระบบ',
            self::TYPE_MAGENTO => 'ข้อมูลจากระบบเก่า',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getStatusOptions($code = null) {
        $ret = [
            self::STATUS_NEW => 'รอการชำระเงิน',
            self::STATUS_TRANSFER_CHECK => 'กำลังตรวจสอบยอดโอน',
            self::STATUS_PAID => 'ชำระเงินเรียบร้อย',
            self::STATUS_DELIVERIED => 'จัดส่งสินค้า',
            self::STATUS_CANCELLED => 'ยกเลิกรายการ',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getStatusOptionsForSearch() {
        $ret = [];
        $ret['-1'] = '(เฉพาะรายการที่รอดำเนินการ)';
        foreach (self::getStatusOptions() as $key => $value) {
            $ret[$key] = $value;
        }
        return $ret;
    }

    public static function getStatusCss($code = null) {
        $ret = [
            self::STATUS_NEW => 'text-danger',
            self::STATUS_TRANSFER_CHECK => 'text-primary',
            self::STATUS_PAID => 'text-success',
            self::STATUS_DELIVERIED => 'text-primary text-bold',
            self::STATUS_CANCELLED => 'text-danger',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getPaymentMethodOptions($code = null) {
        $ret = [
            self::METHOD_TRANSFER => 'โอนเงินผ่านธนาคาร',
            self::METHOD_CREDITCARD => 'ชำระเงินผ่านบัตรเครดิต',
            self::METHOD_COUNTERSERVICE => 'ชำระผ่านเคาเตอร์เซอวิส',
            self::METHOD_MAGENTO => 'นำเข้าโดยระบบ Magento',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getDeliveryOptions($code = null) {
        $ret = [
            self::DELIVERY_FREE => 'ฟรีค่าจัดส่ง',
            self::DELIVERY_REGISTER => 'ไปรษณีย์ลงทะเบียน',
            self::DELIVERY_ALPHA => 'บริการขนส่งเอกชน Alpha (เฉพาะ กรุงเทพฯ และปริมณฑล)',
            self::DELIVERY_EMS => 'ไปรษณีย์ด่วนพิเศษ (EMS)',
            self::DELIVERY_CUSTOM => 'จัดส่งแบบลงทะเบียน (ราคาตามน้ำหนัก)',
            self::DELIVERY_AIRMAIL => 'ไปรษณีย์อากาศ (Airmail)',
            self::DELIVERY_STD => 'จัดส่งธรรมดา',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getDeliveryWithFeeOptions($code = null) {
        $ret = [
            self::DELIVERY_FREE => 'ฟรีค่าจัดส่ง',
            self::DELIVERY_REGISTER => 'ค่าบริการจัดส่งแบบลงทะเบียน ' . intval(Configuration::getValue('delivery_thaipost')) . ' บาท',
            self::DELIVERY_ALPHA => 'บริการขนส่งเอกชน Alpha (เฉพาะ กรุงเทพฯ และปริมณฑล) - ค่าบริการ ' . intval(Configuration::getValue('delivery_alpha')) . ' บาท',
            self::DELIVERY_EMS => 'ไปรษณีย์ด่วนพิเศษ (EMS)',
            self::DELIVERY_CUSTOM => 'จัดส่งแบบลงทะเบียน (ราคาตามน้ำหนัก)',
            self::DELIVERY_AIRMAIL => 'ไปรษณีย์อากาศ (Airmail)',
            self::DELIVERY_STD => 'ค่าบริการจัดส่งแบบธรรมดา',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function getDeliveryTextOptions($code = null, $fee = 0) {
        if (!$code) {
            return '';
        }
        $ret = [
            self::DELIVERY_FREE => 'ส่งฟรี',
            self::DELIVERY_REGISTER => 'ปณ.',
            self::DELIVERY_ALPHA => 'Alpha',
            self::DELIVERY_EMS => 'EMS (' . Yii::$app->formatter->asDecimal($fee, 2) . ' ฿)',
            self::DELIVERY_CUSTOM => 'ตามน้ำหนัก (' . Yii::$app->formatter->asDecimal($fee, 2) . ' ฿)',
            self::DELIVERY_AIRMAIL => 'Air (' . Yii::$app->formatter->asDecimal($fee, 2) . ' ฿)',
            self::DELIVERY_STD => 'จัดส่งธรรมดา (' . Yii::$app->formatter->asDecimal($fee, 2) . ' ฿)',
        ];
        return isset($code) ? (isset($ret[$code]) ? $ret[$code] : '') : $ret;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'buyer_firstname' => 'ชื่อ',
            'buyer_lastname' => 'นามสกุล',
            'buyer_email' => 'อีเมล์',
            'buyer_phone' => 'เบอร์โทรศัพท์',
            'purchase_no' => 'เลขที่ใบสั่งซื้อ',
            'delivery_same' => 'ใช้ข้อมูลเดียวกันกับข้อมูลสำหรับออกใบเสร็จ',
            'invoice_firstname' => 'ชื่อ',
            'invoice_lastname' => 'นามสกุล',
            'invoice_idcard' => 'หมายเลขบัตรประชาชน',
            'invoice_address' => 'ที่อยู่',
            'invoice_postcode' => 'รหัสไปรษณีย์',
            'invoice_province' => 'จังหวัด',
            'invoice_tax' => 'เลขประจำตัวผู้เสียภาษี',
            'invoice_company' => 'บริษัท/หน่วยงาน',
            'invoice_phone' => 'โทรศัพท์',
            'invoice_country' => 'ประเทศ',
            'invoice_comment' => 'ข้อความเพิ่มเติม',
            'delivery_firstname' => 'ชื่อ',
            'delivery_lastname' => 'นามสกุล',
            'delivery_idcard' => 'หมายเลขบัตรประชาชน',
            'delivery_address' => 'ที่อยู่',
            'delivery_postcode' => 'รหัสไปรษณีย์',
            'delivery_province' => 'จังหวัด',
            'delivery_tax' => 'เลขประจำตัวผู้เสียภาษี',
            'delivery_company' => 'บริษัท/หน่วยงาน',
            'delivery_phone' => 'โทรศัพท์',
            'delivery_country' => 'ประเทศ',
            'delivery_comment' => 'ข้อความเพิ่มเติม',
            'transfer_bank_origin' => 'บัญชีที่โอน',
            'transfer_bank' => 'ธนาคาร',
            'transfer_date' => 'วันที่โอน',
            'transfer_time' => 'เวลาโอน',
            'transfer_amount' => 'จำนวนเงิน',
            'status' => 'สถานะ',
            'created_at' => 'วันที่สั่งซื้อ',
            'price_grand' => 'จำนวนเงินที่ต้องชำระ',
            'amount' => 'รายการ',
            'payment_info' => 'ข้อมูลการชำระเงิน',
            'payment_date' => 'วันที่ชำระเงิน',
            'payment_method' => 'ช่องทางการชำระเงิน',
            'status_comment' => 'หมายเหตุ',
            'transfer_file' => 'แนบหลักฐาน',
            'transferFile' => 'แนบหลักฐาน',
            'delivery_method' => 'วิธีการจัดส่ง',
            'login_type' => 'ประเภทล็อคอิน',
            'order_note' => 'หมายเหตุ',
        ]);
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function afterFind() {
        parent::afterFind();
        try {
            $this->gift_info = Json::decode($this->gift_info);
        } catch (InvalidArgumentException $e) {
            
        }
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if ($this->delivery_same) {
                $this->delivery_firstname = $this->invoice_firstname;
                $this->delivery_lastname = $this->invoice_lastname;
                $this->delivery_company = $this->invoice_company;
                $this->delivery_tax = $this->invoice_tax;
                $this->delivery_address = $this->invoice_address;
                $this->delivery_province = $this->invoice_province;
                $this->delivery_postcode = $this->invoice_postcode;
                $this->delivery_phone = $this->invoice_phone;
                $this->delivery_country = $this->invoice_country;
            }
            if ($this->gift_info && is_array($this->gift_info)) {
                $this->gift_info = Json::encode($this->gift_info);
            }
            return true;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            PurchaseStatus::deleteAll([
                'purchase_id' => $this->id,
            ]);
            PurchaseProduct::deleteAll([
                'purchase_id' => $this->id,
            ]);
            return true;
        }
    }

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios['check'] = ['purchase_no'];
        $scenarios['transfer'] = [
            'transfer_bank_origin',
            'transfer_bank',
            'transfer_date',
            'transfer_time',
            'transfer_amount',
            'transferFile',
        ];
        $scenarios['check-member'] = [
            'buyer_firstname',
            'buyer_lastname',
            'buyer_email',
        ];
        $scenarios['check-invoice'] = [
            'invoice_firstname',
            'invoice_lastname',
            'invoice_address',
            'invoice_postcode',
            'invoice_province',
            'invoice_country',
            'invoice_comment',
        ];
        $scenarios['check-delivery'] = [
            'delivery_firstname',
            'delivery_lastname',
            'delivery_address',
            'delivery_postcode',
            'delivery_province',
            'delivery_country',
            'delivery_method',
            'delivery_comment',
        ];
        $scenarios['checkout-method'] = [
            'delivery_method'
        ];
        $scenarios['checkout-payment'] = [
            'payment_method'
        ];
        return $scenarios;
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['invoice_comment', 'delivery_comment'], 'safe'];
        $rules[] = [
            [
                'transfer_bank_origin',
                'transfer_bank',
                'transfer_date',
                'transfer_time',
                'transfer_amount',
            ],
            'required',
            'on' => 'transfer',
        ];
        $rules[] = ['transferFile', 'file', 'extensions' => ['zip', 'png', 'jpg', 'pdf', 'jpeg'], 'wrongExtension' => 'อัพโหลดได้เฉพาะไฟล์นามสกุล jpg, png, pdf, zip เท่านั้น', 'checkExtensionByMimeType' => false, 'skipOnEmpty' => false, 'on' => 'transfer'];
        $rules[] = ['buyer_email', 'email'];
        $rules[] = ['gift_info', 'safe'];
        $rules[] = ['purchase_no', 'required', 'on' => 'check'];
        $rules[] = ['purchase_no', 'exist', 'on' => 'check'];

        $rules[] = ['status', 'required', 'on' => 'checkout'];

        $rules[] = ['search', 'safe', 'on' => 'search'];

        $rules[] = [
            [
                'buyer_firstname',
                'buyer_lastname',
                'buyer_email',
            ],
            'required',
            'on' => 'checkout-member',
        ];
        $rules[] = [
            [
                'invoice_firstname',
                'invoice_lastname',
            ],
            'safe',
            'on' => [
                'checkout-invoice',
                'checkout-final',
            ],
        ];

        $rules[] = [
            [
                'invoice_address',
                'invoice_postcode',
                'invoice_province',
                'invoice_country',
            ],
            'required',
            'on' => 'checkout-invoice',
        ];
        $rules[] = [
            [
                'delivery_firstname',
                'delivery_lastname',
                'delivery_address',
                'delivery_postcode',
                'delivery_province',
                'delivery_country',
            ],
            'required',
            'on' => 'checkout-delivery',
        ];
        $rules[] = [
            [
                'delivery_method',
            ],
            'required',
            'on' => 'checkout-method',
        ];
        $rules[] = [
            [
                'payment_method',
            ],
            'required',
            'on' => 'checkout-payment',
        ];
        $rules[] = [
            [
                'buyer_firstname',
                'buyer_lastname',
                'invoice_address',
                'invoice_postcode',
                'invoice_province',
                'invoice_country',
                'delivery_firstname',
                'delivery_lastname',
                'delivery_address',
                'delivery_postcode',
                'delivery_province',
                'delivery_country',
                'delivery_method',
            ],
            'required',
            'on' => 'checkout-final',
        ];
        $rules[] = [
            ['checkPromotion'],
            'validatePromotion',
            'on' => 'checkout-final',
        ];
        $rules[] = [
            ['delivery_method'],
            'validateDelivery',
            'on' => 'checkout-final',
        ];
        return $rules;
    }

    public function validatePromotion() {
        
    }

    public function validateDelivery() {
        
    }

    public function validateCoupon($attribute) {
        $code = $this->{$attribute};
        if ($code) {
            $model = Coupon::findOne([
                        'code' => $code,
            ]);
            if (isset($model) && $model->isUsable) {
                return true;
            } else {
                $this->addError($attribute, 'ไม่พบรหัสส่วนลดนี้');
                return false;
            }
        }
        $this->addError($attribute, 'ไม่สามารถใช้รหัสส่วนลดนี้ได้');
        return false;
    }

    public function getPurchaseProducts() {
        return $this->hasMany(PurchaseProduct::className(), ['purchase_id' => 'id']);
    }

    public function getInvoiceCountry() {
        return $this->hasOne(Country::class, ['country_code' => 'invoice_country']);
    }

    public function getDeliveryCountry() {
        return $this->hasOne(Country::class, ['country_code' => 'delivery_country']);
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('member_id', $this->member_id);
        $query->andFilterCompare('payment_method', $this->payment_method);
        $query->andFilterCompare('delivery_method', $this->delivery_method);

        if ($this->status == '-1') {
            $query->scopeWaitForAction();
        } else {
            $query->andFilterCompare('status', $this->status);
        }

        if (isset($this->search['text'])) {
            $text = $this->search['text'];

            $range = explode('-', $text);
            /* Range Search */
            if (count($range) == 2 && strlen(trim($range[0])) === 8 && strlen(trim($range[1])) === 8) {
                $start = (int) $range[0];
                $end = (int) $range[1];
                if ($start > $end) {
                    $tmp = $end;
                    $end = $start;
                    $start = $tmp;
                }
                $query->andWhere(['>=', 'purchase_no', $start]);
                $query->andWhere(['<=', 'purchase_no', $end]);
            } else {
                $words = preg_split('/\s+/', $text);
                foreach ($words as $word) {
                    $query->andWhere(['OR', ['OR', ['LIKE', 'purchase_no', $word], ['LIKE', 'buyer_email', $word]], ['OR', ['LIKE', 'buyer_firstname', $word], ['LIKE', 'buyer_lastname', $word]]]);
                }
            }
        }

        if (isset($this->search['ids']) && $this->search['ids']) {
            $ids = explode(',', $this->search['ids']);
            if (count($ids)) {
                $query->andWhere(['id' => $ids]);
            }
        }

        if (isset($this->search['date_start']) && $this->search['date_start']) {
            $query->andWhere(['>=', 'DATE(created_at)', $this->search['date_start']]);
        }

        if (isset($this->search['date_end']) && $this->search['date_end']) {
            $query->andWhere(['<=', 'DATE(created_at)', $this->search['date_end']]);
        }


        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function doCheckout(Cart $cart) {
        if (!$this->payment_method) {
            $this->payment_method = Purchase::METHOD_TRANSFER;
        }
        if ($this->delivery_same) {
            $this->delivery_firstname = $this->invoice_firstname;
            $this->delivery_lastname = $this->invoice_lastname;
            $this->delivery_idcard = $this->invoice_idcard;
            $this->delivery_company = $this->invoice_company;
            $this->delivery_tax = $this->invoice_tax;
            $this->delivery_address = $this->invoice_address;
            $this->delivery_tambon = $this->invoice_tambon;
            $this->delivery_amphur = $this->invoice_amphur;
            $this->delivery_province = $this->invoice_province;
            $this->delivery_postcode = $this->invoice_postcode;
            $this->delivery_phone = $this->invoice_phone;
            $this->delivery_comment = $this->invoice_comment;
        }
        $this->order_date = date('Y-m-d H:i:s');
        $this->purchase_no = '0';
        $this->login_type = Yii::$app->session->get('login_type', 'No Login');

        /* Final check stock before checkout */
        $items = $cart->getItems();
        foreach ($items as $item_id => $item) {
            $product = Product::findOne($item_id);
            if ($product->stock_est - $item['amount'] < 0) {
                $this->addError('id', 'ไม่สามารถทำรายการสั่งซื้อได้ เนื่องจาก ' . Html::encode($product->name) . ' สินค้าหมดสต๊อก');
                return false;
            }
        }

        /* Final check gift stock before checkout */
        if (isset($this->gift_info)) {
            $gift = Gift::findOne(ArrayHelper::getValue($this, 'gift_info.id'));
            if (isset($gift)) {
                if ($gift->stock_est - ArrayHelper::getValue($this, 'gift_info.amount') < 0) {
                    Yii::$app->session->set('gift', null);
                    $this->addError('id', 'ไม่สามารถทำรายการสั่งซื้อได้ เนื่องจากของแถม ' . Html::encode($gift->name) . ' สินค้าหมดสต๊อก');
                    return false;
                }
            }
            $note = [];
            $note[] = '*แถม* ' . ArrayHelper::getValue($this, 'gift_info.value');
            $this->order_note = implode(', ', $note);
        }

        /* Final check delivery fee */
        $this->delivery_weight = $cart->getDeliveryWeight();
        $this->delivery_fee = $cart->getDeliveryTotal();

        /* Check delivery choice */
        $deliveryChoices = $cart->getDeliveryWithFeeOptions();
        if (!array_key_exists($this->delivery_method, $deliveryChoices)) {
            $this->addError('delivery_method', 'กรุณาเลือกวิธีการจัดส่ง');
            $this->delivery_method = null;
            return false;
        }

        if ($this->save()) {
            $items = $cart->getItems();
            foreach ($items as $item_id => $item) {
                $model = $item['model'];
                $pp = new PurchaseProduct;
                $pp->purchase_id = $this->id;
                $pp->product_id = $item_id;
                $pp->price = $item['price'];
                $pp->amount = $item['amount'];
                $pp->price_total = $item['total'];
                $pp->weight = $item['weight'];
                $pp->weight_total = $item['totalWeight'];
                $pp->sku = $model->sku;
                $pp->name = $model->name;

                if (isset($item['addon'])) {
                    $pp->addon_id = $item['addon']->id;
                    $pp->addon_name = $item['addon']->name;
                    $pp->price_addon = $item['addon']->price;
                }

                $pp->save();

                /* Stock Est */
                $product = Product::findOne($item_id);
                $stock = $product->stock_est - $item['amount'];
                $product->updateAttributes([
                    'stock_est' => $stock,
                ]);

                /* Gift Stock Est */
                if (isset($this->gift_info)) {
                    $info = is_array($this->gift_info) ? $this->gift_info : Json::decode($this->gift_info);
                    $gift = Gift::findOne(ArrayHelper::getValue($info, 'id'));
                    $gift->updateAttributes([
                        'stock_est' => $gift->stock_est - ArrayHelper::getValue($info, 'amount', 1),
                    ]);
                }
            }

            $items = $cart->getProducts();
            foreach ($items as $item_id => $item) {
                $model = $item['model'];
                $pp = new PurchaseProduct;
                $pp->purchase_id = $this->id;
                $pp->product_id = $item_id;
                $pp->price = 0;
                $pp->amount = $item['amount'];
                $pp->price_total = 0;
                $pp->weight = $item['weight'];
                $pp->weight_total = $item['totalWeight'];
                $pp->sku = $model->sku;
                $pp->name = $model->name;

                if (isset($item['addon'])) {
                    $pp->addon_id = $item['addon']->id;
                    $pp->addon_name = $item['addon']->name;
                    $pp->price_addon = $item['addon']->price;
                }

                $pp->save();

                /* Stock Est */
                $product = Product::findOne($item_id);
                $stock = $product->stock_est - $item['amount'];
                $product->updateAttributes([
                    'stock_est' => $stock,
                ]);

                /* Gift Stock Est */
                if (isset($this->gift_info)) {
                    $info = is_array($this->gift_info) ? $this->gift_info : Json::decode($this->gift_info);
                    $gift = Gift::findOne(ArrayHelper::getValue($info, 'id'));
                    $gift->updateAttributes([
                        'stock_est' => $gift->stock_est - ArrayHelper::getValue($info, 'amount', 1),
                    ]);
                }
            }

            $promotion = $cart->getPromotionSummary($this);
            $this->updateAttributes([
                'purchase_no' => 10000000 + $this->id,
                'price_total' => $cart->getTotal(),
                'price_grand' => $cart->getGrandTotal(),
                'price_discount' => $cart->getDiscountTotal(),
                'promotion_data' => Json::encode($promotion),
            ]);
            $cart->clear();

            try {
                $mail = new MicMailer;
                $mail->setView('invoice', [
                    '{{doc_no}}' => $this->purchase_no,
                    '{{name}}' => $this->buyerFullname,
                    '{{info}}' => $this->getHtmlOrder(),
                    '{{description}}' => '',
                    '{{order_url}}' => Html::a('คลิ๊กที่นี่', Url::to(['/order/view', 'order_no' => $this->purchase_no], true)),
                ]);
                $mail->send([$this->getEmail()]);
            } catch (Exception $e) {
                Yii::error($e->getMessage());
            }
            return true;
        }
    }

    public function getEmail() {
        $mail = isset($this->member) ? $this->member->username : $this->buyer_email;
        if ($mail) {
            return $mail;
        }
        return Configuration::getValue('mail_from');
    }

    public function doTransfer($sendmail = true) {
        $this->transferFile = UploadedFile::getInstance($this, 'transferFile');
        if ($this->save()) {
            $filename = uniqid('transfer_') . '.' . $this->transferFile->extension;
            $this->transferFile->saveAs(Yii::getAlias('@app/uploads/transfer/') . $filename);
            $this->updateAttributes([
                'transfer_file' => $filename,
                'status' => self::STATUS_TRANSFER_CHECK,
            ]);

            if ($sendmail) {
                $mail = new MicMailer;
                $mail->setView('transfer', [
                    '{{doc_no}}' => $this->purchase_no,
                    '{{buyer_name}}' => $this->buyerFullname,
                    '{{buyer_email}}' => $this->getEmail(),
                    '{{transfer_amount}}' => Yii::$app->formatter->asDecimal($this->transfer_amount, 2) . ' บาท',
                    '{{transfer_bank}}' => ArrayHelper::getValue($this->transferBank, 'shortName'),
                    '{{transfer_date}}' => $this->transfer_date . ' ' . $this->transfer_time,
                    '{{buyer_phone}}' => $this->buyer_phone,
                ]);
                $mail->setFrom($this->getEmail());
                $mail->setReply($this->getEmail());
                $mail->setReturnPath($this->getEmail());
                $mail->addAttachment(Yii::getAlias('@app/uploads/transfer/') . $filename);
                $mail->send(Configuration::getValue('web_mail'), false);
            }

            return true;
        }
    }

    public function loadCart(Cart $cart) {
        $free = Configuration::getValue('delivery_free');
        $this->price_total = $cart->getTotal();
        $this->delivery_fee = $cart->getDeliveryTotal();
        $this->price_grand = $cart->getGrandTotal();

        if (isset($cart->coupon)) {
            $coupon = $cart->coupon;
            $this->coupon_id = $coupon->id;
            $this->coupon_code = $coupon->code;
            $this->coupon_detail = $coupon->couponDetail;
        }
    }

    public function getBuyerFullname() {
        if (isset($this->member)) {
            return $this->member->firstname . ' ' . $this->member->lastname;
        }
        return $this->buyer_firstname . ' ' . $this->buyer_lastname;
    }

    public function getBuyerFullnameWithId() {
        $html = [];
        $html[] = Html::tag('div', $this->getBuyerFullname());
        if ($this->invoice_idcard) {
            $html[] = Html::tag('div', Html::tag('small', $this->invoice_idcard));
        }
        if ($this->invoice_company) {
            $html[] = Html::tag('div', $this->invoice_company);
        }
        if ($this->invoice_tax) {
            $html[] = Html::tag('div', Html::tag('small', $this->invoice_tax));
        }
        return implode('', $html);
    }

    public function getTransferBank() {
        return $this->hasOne(Bank::className(), ['id' => 'transfer_bank_origin']);
    }

    public function getHtmlStatus() {
        return Html::tag('span', self::getStatusOptions($this->status), ['class' => self::getStatusCss($this->status)]);
    }

    public function getIsTransferNotice() {
        if ($this->payment_method == self::METHOD_TRANSFER && in_array($this->status, [
                    self::STATUS_TRANSFER_CHECK,
                ])) {
            return true;
        }
    }

    public function getIsWaitForTransfer() {
        if ($this->payment_method == self::METHOD_TRANSFER && in_array($this->status, [
                    self::STATUS_NEW,
                    self::STATUS_TRANSFER_CHECK,
                ])) {
            return true;
        }
    }

    public function getHtmlRemark() {
        return $this->payment_info;
    }

    public function getPossibleStatusOptions() {
        return [];
    }

    public function getIsPaid() {
        return $this->is_paid ? true : false;
    }

    public function getIsDelivery() {
        return $this->is_paid && $this->status == self::STATUS_DELIVERIED;
    }

    public function doReorder() {
        $cart = Yii::$app->session->get('cart');
        if (!isset($cart)) {
            Yii::$app->session->set('cart', new Cart);
            $cart = Yii::$app->session->get('cart');
        }

        $cart->clear();
        foreach ($this->purchaseProducts as $item) {
            $cart->setItem($item->product, $item->amount);
        }
        return true;
    }

    public function doDelivery($msg, $updateTransaction = true, $sendmail = false) {
        if ($updateTransaction) {
            $status = new PurchaseStatus;
            $status->purchase_id = $this->id;
            $status->status = self::STATUS_DELIVERIED;
            $status->is_sendmail = 1;
            $status->description = $msg;
            return $status->save();
        } else {
            $this->status = self::STATUS_DELIVERIED;
            $this->status_comment = $msg;
            if ($this->save()) {
                if ($sendmail) {
                    $mail = new MicMailer;
                    $mail->setView('delivery', [
                        '{{doc_no}}' => $this->purchase_no,
                        '{{name}}' => $this->buyerFullname,
                        '{{info}}' => $this->getHtmlCart(),
                        '{{description}}' => $msg,
                        '{{order_url}}' => Html::a('คลิ๊กที่นี่', Url::to(['/order/view', 'order_no' => $this->purchase_no], true)),
                    ]);
                    $mail->send([$this->getEmail()]);
                }
            }
        }
    }

    public function doPaid($msg, $updateTransaction = true, $sendmail = true) {
        if ($updateTransaction) {
            $status = new PurchaseStatus;
            $status->purchase_id = $this->id;
            $status->status = self::STATUS_PAID;
            $status->is_sendmail = 1;
            $status->description = $msg;
            return $status->save();
        } else {
            $this->is_paid = 1;
            $this->status = self::STATUS_PAID;
            $this->status_comment = $msg;
            $this->payment_info = $msg;
            $this->payment_date = date('Y-m-d H:i:s');
            if ($this->save()) {

                /* Stock thing */
                foreach ($this->purchaseProducts as $item) {
                    $product = Product::findOne($item->product_id);
                    $product->updateAttributes([
                        'stock' => $product->stock - $item->amount,
                    ]);
                }
                /* Gift Stock */
                if (isset($this->gift_info)) {
                    $info = is_array($this->gift_info) ? $this->gift_info : Json::decode($this->gift_info);
                    $gift = Gift::findOne(ArrayHelper::getValue($info, 'id'));
                    $gift->updateAttributes([
                        'stock' => $gift->stock - ArrayHelper::getValue($info, 'amount', 1),
                    ]);
                }
                if ($sendmail) {
                    $mail = new MicMailer;
                    $mail->setView('receipt', [
                        '{{doc_no}}' => $this->purchase_no,
                        '{{name}}' => $this->buyerFullname,
                        '{{info}}' => $this->getHtmlOrder(false),
                        '{{description}}' => $msg,
                        '{{order_url}}' => Html::a('คลิ๊กที่นี่', Url::to(['/order/view', 'order_no' => $this->purchase_no], true)),
                    ]);
                    $mail->send([$this->getEmail()]);
                }
                return true;
            } else {
                echo '<pre>';
                var_dump($this->errors);
                exit;
            }
        }
    }

    public function getHtmlOrder($includePayment = true) {
        $html = [];
        $html[] = '<h5 style="margin-bottom:0px;border-bottom:2px solid #ccc;padding-bottom:3px;">เลขที่ใบสั่งซื้อของท่าน #' . $this->purchase_no . ' (สั่งซื้อเมื่อ ' . Yii::$app->formatter->asDate($this->order_date) . ')</h5>';
        $html[] = '<table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
        $html[] = '<tr>';
        $html[] = '<td width="50%" style="vertical-align:top;padding-right:3px;">';
        $html[] = '
            <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">ที่อยู่สำหรับออกใบเสร็จ</h4>
            <div style="margin:5px;">
                <div>' . Html::encode($this->invoice_firstname) . ' ' . Html::encode($this->invoice_lastname) . '</div>';
        $html[] = ($this->invoice_idcard) ? '<div>เลขบัตรประชาชน : ' . Html::encode($this->invoice_idcard) . '</div>' : '';
        $html[] = '
                <div>' . Html::encode($this->invoice_company) . ' ' . Html::encode($this->invoice_tax) . '</div>
                <div>' . Html::encode($this->invoice_address) . '</div>
                <div>' . ($this->invoice_tambon ? 'แขวง/ตำบล ' . Html::encode($this->invoice_tambon) : '') . ' ' . ($this->invoice_amphur ? 'เขต/อำเภอ ' . Html::encode($this->invoice_amphur) : '') . '</div>
                <div>' . Html::encode($this->invoice_province) . ' ' . Html::encode($this->invoice_postcode) . '</div>
                <div>โทร : ' . Html::encode($this->invoice_phone) . '</div>
            </div>

            <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">ที่อยู่สำหรับจัดส่งสินค้า</h4>
            <div style="margin:5px;">
                <div>' . Html::encode($this->delivery_firstname) . ' ' . Html::encode($this->delivery_lastname) . '</div>';
        $html[] = ($this->delivery_idcard) ? '<div>เลขบัตรประชาชน : ' . Html::encode($this->delivery_idcard) . '</div>' : '';
        $html[] = '        
                <div>' . Html::encode($this->delivery_company) . ' ' . Html::encode($this->delivery_tax) . '</div>
                <div>' . Html::encode($this->delivery_address) . '</div>
                <div>' . ($this->delivery_tambon ? 'แขวง/ตำบล ' . Html::encode($this->delivery_tambon) : '') . ' ' . ($this->delivery_amphur ? 'เขต/อำเภอ ' . Html::encode($this->delivery_amphur) : '') . '</div>
                <div>' . Html::encode($this->delivery_province) . ' ' . Html::encode($this->delivery_postcode) . '</div>
                <div>โทร : ' . Html::encode($this->delivery_phone) . '</div>
            </div>';
        $html[] = '</td>';

        if ($includePayment) {
            $html[] = '<td width="50%" style="vertical-align:top;padding-left:3px;">';
            $html[] = '
            <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">วิธีการชำระเงิน</h4>
            <div style="margin:5px;">
                ' . $this->getPaymentMethod() . '
                ' . $this->getPaymentInfo() . '    
            </div>';
            $html[] = '
            <h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">วิธีการจัดส่ง</h4>
            <div style="margin:5px;">
                ' . $this->getDeliveryMethod() . '
            </div>';
            $html[] = '</td>';
        }
        $html[] = '</tr>';
        $html[] = '</table>';
        $html[] = '<h4 style="margin-top:1px;margin-bottom:1px;padding:0.3em 0.5em; background:#ccc;">รายการสินค้า</h4>';
        $html[] = $this->getHtmlCart();
        return implode("\n", $html);
    }

    public function getPaymentInfo() {
        $html = [];
        $html[] = '<div>';
        if ($this->payment_method == self::METHOD_TRANSFER) {
            $banks = Bank::find()->andWhere(['is_enabled' => 1])->orderBy(['order_no' => SORT_ASC])->all();
            foreach ($banks as $bank) {
                $html[] = '<table width="100%" style="margin:10px 0;font-size:0.9em;">';
                $html[] = '<tr><th width="35%" style="text-align:right;">ชื่อบัญชี</th><td style="padding-left:15px;">' . Html::encode($bank->account_name) . '</td></tr>';
                $html[] = '<tr><th width="35%" style="text-align:right;">เลขบัญชี</th><td style="padding-left:15px;">' . Html::encode($bank->account_no) . '</td></tr>';
                $html[] = '<tr><th width="35%" style="text-align:right;">ประเภท</th><td style="padding-left:15px;">' . Html::encode($bank->account_type) . '</td></tr>';
                $html[] = '<tr><th width="35%" style="text-align:right;">ชื่อธนาคาร</th><td style="padding-left:15px;">' . Html::encode($bank->name) . '</td></tr>';
                $html[] = '</table>';
            }
        }
        $html[] = '</div>';
        return implode("\n", $html);
    }

    public function getPaymentInfoPdf() {
        $html = [];
        $html[] = '<div>';
        if ($this->payment_method == self::METHOD_TRANSFER) {
            $banks = Bank::find()->active()->all();
            foreach ($banks as $bank) {
                $html[] = '<table width="100%" style="margin:10px 0;">';
                $html[] = '<tr><th width="35%" style="text-align:right;">ชื่อบัญชี</th><td style="padding-left:15px;">' . Html::encode($bank->account_name) . '</td></tr>';
                $html[] = '<tr><th width="35%" style="text-align:right;">เลขบัญชี</th><td style="padding-left:15px;">' . Html::encode($bank->account_no) . '</td></tr>';
                $html[] = '<tr><th width="35%" style="text-align:right;">ประเภท</th><td style="padding-left:15px;">' . Html::encode($bank->account_type) . '</td></tr>';
                $html[] = '<tr><th width="35%" style="text-align:right;">ชื่อธนาคาร</th><td style="padding-left:15px;">' . Html::encode($bank->name) . '</td></tr>';
                $html[] = '</table>';
            }
        }
        $html[] = '</div>';
        return implode("\n", $html);
    }

    public function getHtmlCart() {
        $html = [];
        $html[] = '<table width="100%" border="1" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">';
        $html[] = '<tr><th>#</th><th>สินค้า</th><th>ราคา</th><th>จำนวน</th><th>รวม</th></tr>';
        foreach ($this->purchaseProducts as $count => $item) {
            $html[] = '
                <tr>
                    <td>' . ($count + 1) . '</td>
                    <td>' . Html::encode($item->name) . '</td>
                    <td style="text-align:right;">' . Yii::$app->formatter->asDecimal($item->price, 2) . '</td>
                    <td style="text-align:center;">' . Yii::$app->formatter->asInteger($item->amount) . '</td>
                    <td style="text-align:right;">' . Yii::$app->formatter->asDecimal($item->price_total, 2) . '</td>
                </tr>
            ';
        }

        $promotions = $this->getPromotionSummary();
        if (isset($promotions['info'])) {
            foreach ($promotions['info'] as $promotion) {
                if (isset($promotion['showOnCart']) && !$promotion['showOnCart']) {
                    continue;
                }
                $html[] = '                   
                <tr>
                    <td colspan="4" style="text-align:right;">' . $promotion['name'] . '</td>
                    <td style="text-align:right;">' . ($promotion['discount'] ? '- ' . Yii::$app->formatter->asDecimal($promotion['discount'], 2) : 'ฟรี') . '</td>
                </tr>';
            }
        }

        $html[] = '
                <tr>
                    <td colspan="4" style="text-align:right;">ราคารวม :</td>
                    <td style="text-align:right;">' . Yii::$app->formatter->asDecimal($this->price_total, 2) . '</td>
                </tr> 
        ';
        $html[] = '
                <tr>
                    <td colspan="4" style="text-align:right;">ค่าจัดส่ง (<span class="text-info">' . $this->getDeliveryMethod() . '</span>) :</td>
                    <td style="text-align:right;">' . ($this->delivery_fee ? Yii::$app->formatter->asDecimal($this->delivery_fee, 2) : '-') . '</td>
                </tr> 
        ';
        $html[] = '
                <tr>
                    <td colspan="4" style="text-align:right;">จำนวนเงินที่ต้องชำระ :</td>
                    <td style="text-align:right;">' . Yii::$app->formatter->asDecimal($this->price_grand, 2) . '</td>
                </tr> 
        ';
        $html[] = '</table>';
        return implode("\n", $html);
    }

    public function doWarnPayment() {
        $mail = new MicMailer;
        $mail->setView('warn', [
            '{{doc_no}}' => $this->purchase_no,
            '{{name}}' => $this->buyerFullname,
            '{{info}}' => $this->getHtmlOrder(),
            '{{order_date}}' => date('j F Y H:i:s', strtotime($this->order_date)),
            '{{expire_date}}' => date('j F Y', strtotime('+' . Yii::$app->params['orderExpireDay'] . ' days', strtotime($this->order_date))),
            '{{description}}' => '',
            '{{order_url}}' => Html::a('คลิกที่นี่', Url::to(['/order/view', 'order_no' => $this->purchase_no], true)),
        ]);
        return $mail->send([$this->getEmail()]);
    }

    public function doCancelPayment($msg = null, $updateTransaction = true) {
        if ($updateTransaction) {
            $status = new PurchaseStatus;
            $status->purchase_id = $this->id;
            $status->status = self::STATUS_CANCELLED;
            $status->is_sendmail = 0;
            $status->description = isset($msg) ? $msg : 'เกิดข้อผิดพลาดในการชำระเงิน';
            return $status->save();
        } else {
            $this->status = self::STATUS_CANCELLED;
            $this->payment_info = isset($msg) ? $msg : 'เกิดข้อผิดพลาดในการชำระเงิน';
            $this->payment_date = date('Y-m-d H:i:s');
            if ($this->save()) {

                /* Stock thing */
                foreach ($this->purchaseProducts as $item) {
                    $product = Product::findOne($item->product_id);

                    $stock = $product->stock_est + $item->amount;

                    $product->updateAttributes([
                        'stock' => $product->stock + $item->amount,
                        'stock_est' => $stock,
                    ]);
                }

                /* Gift Stock */
                if (isset($this->gift_info)) {
                    $info = is_array($this->gift_info) ? $this->gift_info : Json::decode($this->gift_info);
                    $gift = Gift::findOne(ArrayHelper::getValue($info, 'id'));
                    $gift->updateAttributes([
                        'stock' => $gift->stock + ArrayHelper::getValue($info, 'amount', 1),
                        'stock_est' => $gift->stock_est + ArrayHelper::getValue($info, 'amount', 1),
                    ]);
                }

                $mail = new MicMailer;
                $mail->setView('cancel', [
                    '{{doc_no}}' => $this->purchase_no,
                    '{{name}}' => $this->buyerFullname,
                    '{{info}}' => $this->getHtmlOrder(),
                    '{{order_date}}' => date('j F Y H:i:s', strtotime($this->order_date)),
                    '{{expire_date}}' => date('j F Y', strtotime('+' . Yii::$app->params['orderExpireDay'] . ' days', strtotime($this->order_date))),
                    '{{description}}' => '',
                    '{{order_url}}' => Html::a('คลิกที่นี่', Url::to(['/order/view', 'order_no' => $this->purchase_no], true)),
                ]);
                $mail->send([$this->getEmail()]);
            }
        }
    }

    public function getDeliveryMethod() {
        if (!$this->delivery_method && $this->magento_id) {
            return '';
        }
        return self::getDeliveryOptions($this->delivery_method);
    }

    public function getDeliveryText() {
        if (!$this->delivery_method && $this->magento_id) {
            return '';
        }
        return self::getDeliveryTextOptions($this->delivery_method, $this->delivery_fee);
    }

    public function getPaymentFields() {
        $ret = [];
        $ret['MERCHANT2'] = ArrayHelper::getValue(Yii::$app->params, 'kbank.merchantId');
        $ret['TERM2'] = ArrayHelper::getValue(Yii::$app->params, 'kbank.term');
        $ret['URL2'] = Url::to(['kbank/cust-response'], true);
        $ret['RESPURL'] = Url::to(['kbank/api-response'], true);
        $ret['IPCUST2'] = Yii::$app->request->remoteIP;
        $ret['DETAIL2'] = $this->getPaymentDetail();
        $ret['INVMERCHANT'] = $this->purchase_no;
        /*
          if (true) {
          $this->price_grand = 1;
          $ret['DETAIL2'] = 'ทดสอบระบบ-ลบได้';
          } */
        $ret['AMOUNT2'] = str_pad(round($this->price_grand * 100, 0), 12, '0', STR_PAD_LEFT);
        return $ret;
    }

    public function getPaymentDetail() {
        $ret = [];
        foreach ($this->products as $product) {
            $ret[] = $product->name;
        }
        return trim(mb_substr(implode(', ', $ret), 0, 30, 'UTF-8'));
    }

    public function getPaymentMethod() {
        return self::getPaymentMethodOptions($this->payment_method);
    }

    public function getPromotionSummary() {
        return Json::decode($this->promotion_data);
    }

    public function hasProducts($ids) {
        foreach ($this->purchaseProducts as $product) {
            if (in_array($product->product_id, $ids)) {
                return true;
            }
        }
        return false;
    }

}

class PurchaseQuery extends ActiveQuery {

    public function scopeWaitForAction() {
        $this->where(['status' => [
                Purchase::STATUS_PAID,
                Purchase::STATUS_TRANSFER_CHECK,
        ]]);
        return $this;
    }

    public function nearlyExpire($day = 2) {
        $expire_date = date('Y-m-d', strtotime('-' . $day . ' days')) . ' 00:00:00';
        $this->andWhere(['status' => Purchase::STATUS_NEW]);
        $this->andWhere(['DATE(created_at)' => $expire_date]);
        return $this;
    }

    public function expire($day = 7) {
        $expire_date = date('Y-m-d', strtotime('-' . $day . ' days')) . ' 00:00:00';
        $this->andWhere(['status' => Purchase::STATUS_NEW]);
        $this->andWhere(['<=', 'created_at', $expire_date]);
        return $this;
    }

}
