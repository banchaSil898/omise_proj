<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "purchase".
 *
 * @property int $id
 * @property int $purchase_type ประเภทการสั่งซื้อ 0 = ไม่สมัครสมาชิก 1 = สมัครใหม่ 2 = เข้าสู่ระบบ
 * @property string $purchase_no
 * @property int $member_id
 * @property int $magento_id
 * @property string $buyer_firstname ชื่อผู้ซื้อ
 * @property string $buyer_lastname นามสกุลผู้ซื้อ
 * @property string $buyer_email
 * @property string $buyer_address
 * @property string $buyer_phone
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property string $status_comment
 * @property string $status_date
 * @property int $amount
 * @property string $price_total
 * @property string $price_grand
 * @property string $coupon_code
 * @property string $ems_code
 * @property string $note
 * @property int $is_paid
 * @property int $payment_method วิธีการชำระเงิน
 * @property string $payment_info
 * @property string $payment_date
 * @property string $payment_data
 * @property string $order_date
 * @property string $invoice_firstname
 * @property string $invoice_lastname
 * @property string $invoice_company
 * @property string $invoice_tax
 * @property string $invoice_postcode
 * @property string $invoice_email
 * @property string $invoice_address
 * @property string $invoice_province
 * @property string $invoice_phone
 * @property int $delivery_same
 * @property string $delivery_firstname
 * @property string $delivery_lastname
 * @property string $delivery_company
 * @property string $delivery_tax
 * @property string $delivery_postcode
 * @property string $delivery_email
 * @property string $delivery_address
 * @property string $delivery_province
 * @property string $delivery_phone
 * @property int $delivery_method
 * @property string $delivery_fee
 * @property string $delivery_note
 * @property string $delivery_date
 * @property string $transfer_bank ธนาคารที่โอน
 * @property string $transfer_date
 * @property string $transfer_time เวลาที่โอน
 * @property string $transfer_amount จำนวนเงินที่โอน
 * @property string $transfer_bank_origin
 * @property string $delivery_weight
 * @property int $magento_quote_id
 * @property int $magento_shipping_address_id
 * @property int $magento_billing_address_id
 * @property int $coupon_id
 * @property string $coupon_detail
 * @property string $coupon_discount
 * @property string $purchase_date
 * @property string $invoice_country
 * @property string $delivery_country
 * @property string $transfer_file
 * @property string $price_discount
 * @property string $promotion_data
 * @property string $login_type
 * @property string $order_note
 * @property string $gift_info
 * @property string $invoice_idcard
 * @property string $delivery_idcard
 * @property string $cart_log
 * @property int $survey_2019_allow
 * @property int $survey_2019_age
 * @property string $survey_2019_graduate
 * @property string $survey_2019_comment
 * @property string $delivery_comment
 * @property string $invoice_comment
 * @property int $addon_id
 * @property string $addon_name
 * @property string $addon_price
 * @property string $invoice_amphur
 * @property string $invoice_tambon
 * @property string $delivery_amphur
 * @property string $delivery_tambon
 *
 * @property Member $member
 * @property PurchaseProduct[] $purchaseProducts
 * @property Product[] $products
 * @property PurchaseStatus[] $purchaseStatuses
 */
class Purchase extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'purchase';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchase_type'], 'required'],
            [['purchase_type', 'member_id', 'magento_id', 'status', 'amount', 'is_paid', 'payment_method', 'delivery_same', 'delivery_method', 'magento_quote_id', 'magento_shipping_address_id', 'magento_billing_address_id', 'coupon_id', 'survey_2019_allow', 'survey_2019_age', 'addon_id'], 'integer'],
            [['buyer_address', 'status_comment', 'note', 'payment_info', 'payment_data', 'invoice_address', 'delivery_address', 'coupon_detail', 'promotion_data', 'order_note', 'gift_info', 'cart_log', 'survey_2019_comment', 'delivery_comment', 'invoice_comment'], 'string'],
            [['created_at', 'updated_at', 'status_date', 'payment_date', 'order_date', 'delivery_note', 'delivery_date', 'transfer_date', 'purchase_date'], 'safe'],
            [['price_total', 'price_grand', 'delivery_fee', 'transfer_amount', 'delivery_weight', 'coupon_discount', 'price_discount', 'addon_price'], 'number'],
            [['purchase_no', 'invoice_idcard', 'delivery_idcard'], 'string', 'max' => 32],
            [['buyer_firstname', 'buyer_lastname', 'invoice_amphur', 'invoice_tambon', 'delivery_amphur', 'delivery_tambon'], 'string', 'max' => 160],
            [['buyer_email'], 'string', 'max' => 100],
            [['buyer_phone', 'coupon_code', 'ems_code', 'invoice_email', 'invoice_province', 'invoice_phone', 'delivery_email', 'delivery_province', 'delivery_phone', 'transfer_bank', 'transfer_time', 'transfer_bank_origin', 'login_type'], 'string', 'max' => 60],
            [['invoice_firstname', 'invoice_lastname', 'invoice_company', 'delivery_firstname', 'delivery_lastname', 'delivery_company'], 'string', 'max' => 150],
            [['invoice_tax', 'delivery_tax'], 'string', 'max' => 30],
            [['invoice_postcode', 'delivery_postcode'], 'string', 'max' => 20],
            [['invoice_country', 'delivery_country'], 'string', 'max' => 3],
            [['transfer_file'], 'string', 'max' => 200],
            [['survey_2019_graduate'], 'string', 'max' => 64],
            [['addon_name'], 'string', 'max' => 128],
            [['member_id'], 'exist', 'skipOnError' => true, 'targetClass' => Member::className(), 'targetAttribute' => ['member_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchase_type' => 'Purchase Type',
            'purchase_no' => 'Purchase No',
            'member_id' => 'Member ID',
            'magento_id' => 'Magento ID',
            'buyer_firstname' => 'Buyer Firstname',
            'buyer_lastname' => 'Buyer Lastname',
            'buyer_email' => 'Buyer Email',
            'buyer_address' => 'Buyer Address',
            'buyer_phone' => 'Buyer Phone',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'status_comment' => 'Status Comment',
            'status_date' => 'Status Date',
            'amount' => 'Amount',
            'price_total' => 'Price Total',
            'price_grand' => 'Price Grand',
            'coupon_code' => 'Coupon Code',
            'ems_code' => 'Ems Code',
            'note' => 'Note',
            'is_paid' => 'Is Paid',
            'payment_method' => 'Payment Method',
            'payment_info' => 'Payment Info',
            'payment_date' => 'Payment Date',
            'payment_data' => 'Payment Data',
            'order_date' => 'Order Date',
            'invoice_firstname' => 'Invoice Firstname',
            'invoice_lastname' => 'Invoice Lastname',
            'invoice_company' => 'Invoice Company',
            'invoice_tax' => 'Invoice Tax',
            'invoice_postcode' => 'Invoice Postcode',
            'invoice_email' => 'Invoice Email',
            'invoice_address' => 'Invoice Address',
            'invoice_province' => 'Invoice Province',
            'invoice_phone' => 'Invoice Phone',
            'delivery_same' => 'Delivery Same',
            'delivery_firstname' => 'Delivery Firstname',
            'delivery_lastname' => 'Delivery Lastname',
            'delivery_company' => 'Delivery Company',
            'delivery_tax' => 'Delivery Tax',
            'delivery_postcode' => 'Delivery Postcode',
            'delivery_email' => 'Delivery Email',
            'delivery_address' => 'Delivery Address',
            'delivery_province' => 'Delivery Province',
            'delivery_phone' => 'Delivery Phone',
            'delivery_method' => 'Delivery Method',
            'delivery_fee' => 'Delivery Fee',
            'delivery_note' => 'Delivery Note',
            'delivery_date' => 'Delivery Date',
            'transfer_bank' => 'Transfer Bank',
            'transfer_date' => 'Transfer Date',
            'transfer_time' => 'Transfer Time',
            'transfer_amount' => 'Transfer Amount',
            'transfer_bank_origin' => 'Transfer Bank Origin',
            'delivery_weight' => 'Delivery Weight',
            'magento_quote_id' => 'Magento Quote ID',
            'magento_shipping_address_id' => 'Magento Shipping Address ID',
            'magento_billing_address_id' => 'Magento Billing Address ID',
            'coupon_id' => 'Coupon ID',
            'coupon_detail' => 'Coupon Detail',
            'coupon_discount' => 'Coupon Discount',
            'purchase_date' => 'Purchase Date',
            'invoice_country' => 'Invoice Country',
            'delivery_country' => 'Delivery Country',
            'transfer_file' => 'Transfer File',
            'price_discount' => 'Price Discount',
            'promotion_data' => 'Promotion Data',
            'login_type' => 'Login Type',
            'order_note' => 'Order Note',
            'gift_info' => 'Gift Info',
            'invoice_idcard' => 'Invoice Idcard',
            'delivery_idcard' => 'Delivery Idcard',
            'cart_log' => 'Cart Log',
            'survey_2019_allow' => 'Survey 2019 Allow',
            'survey_2019_age' => 'Survey 2019 Age',
            'survey_2019_graduate' => 'Survey 2019 Graduate',
            'survey_2019_comment' => 'Survey 2019 Comment',
            'delivery_comment' => 'Delivery Comment',
            'invoice_comment' => 'Invoice Comment',
            'addon_id' => 'Addon ID',
            'addon_name' => 'Addon Name',
            'addon_price' => 'Addon Price',
            'invoice_amphur' => 'Invoice Amphur',
            'invoice_tambon' => 'Invoice Tambon',
            'delivery_amphur' => 'Delivery Amphur',
            'delivery_tambon' => 'Delivery Tambon',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::className(), ['id' => 'member_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseProducts()
    {
        return $this->hasMany(PurchaseProduct::className(), ['purchase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('purchase_product', ['purchase_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchaseStatuses()
    {
        return $this->hasMany(PurchaseStatus::className(), ['purchase_id' => 'id']);
    }

    public function getOmisePayments()
    {
        return $this->hasOne(OmisePayments::className(), ['order_id' => 'id']);
    }
}
