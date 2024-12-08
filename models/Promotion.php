<?php

namespace app\models;

use app\components\promotion\PromotionBirthManager;
use app\components\promotion\PromotionCartBuffetManager;
use app\components\promotion\PromotionCartCouponManager;
use app\components\promotion\PromotionCartDiscountManager;
use app\components\promotion\PromotionCartFreeDeliveryManager;
use app\components\promotion\PromotionCartGiftManager;
use app\components\promotion\PromotionCartPayOneManager;
use app\components\promotion\PromotionCartProductKeyManager;
use app\components\promotion\PromotionCartProductManager;
use app\components\promotion\PromotionCouponManager;
use app\components\promotion\PromotionDiscountManager;
use app\components\promotion\PromotionGeneralManager;
use app\models\base\Promotion as BasePromotion;
use codesk\components\Html;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class Promotion extends BasePromotion {

    const TYPE_DISCOUNT = 1;
    const TYPE_BIRTH = 2;
    const TYPE_PRICE_GET_DISCOUNT_P = 3;
    const TYPE_PRICE_GET_DISCOUNT = 4;
    const TYPE_PRICE_GET_COUPON_P = 5;
    const TYPE_PRICE_GET_THING = 6;
    const TYPE_THING_GET_DISCOUNT_P = 7;
    const TYPE_THING_GET_DISCOUNT = 8;
    const TYPE_GIFT = 9;
    const TYPE_BUFFET = 10;
    const TYPE_COUPON_P = 11;
    const TYPE_COUPON = 12;
    const TYPE_THING_GET_THING = 13;
    const TYPE_THING_GET_DISCOUNT_A = 14;
    const TYPE_FREE_DELIVERY = 15;
    const TYPE_GIFT_BY_THING = 16;
    const TYPE_KEY_TO_DISCOUNT = 17;
    const TYPE_KEY_TO_DISCOUNT_P = 18;
    const TYPE_DISCOUNT_FIX = 19;
    const TYPE_PRICE_GET_COUPON = 20;
    const TYPE_PRICE_GET_DISCOUNT_P_2 = 30;
    const TYPE_PRICE_GET_DISCOUNT_2 = 40;
    const TYPE_THING_GET_COUPON_P = 21;
    const TYPE_THING_GET_COUPON = 22;

    public $search;

    public static function getTypeOptions($code = null) {
        $ret = [
            self::TYPE_DISCOUNT => '01 - ลดราคา y %',
            self::TYPE_DISCOUNT_FIX => '02 - ลดราคาเหลือ y บาท',
            self::TYPE_BIRTH => '02 - ลดราคาเมื่อถึงเดือนเกิด',
            self::TYPE_PRICE_GET_DISCOUNT_P => '03 - ซื้อครบ x บาท ลดราคา y %',
            self::TYPE_PRICE_GET_DISCOUNT => '04 - ซื้อครบ x บาท ลดราคา y บาท',
            self::TYPE_PRICE_GET_COUPON_P => '05.1 - ซื้อครบ x บาท สามารถใช้คูปองลดราคา y %',
            self::TYPE_PRICE_GET_COUPON => '05.2 - ซื้อครบ x บาท สามารถใช้คูปองลดราคา y บาท',
            self::TYPE_THING_GET_COUPON_P => '05.3 - ซื้อครบ x เล่ม สามารถใช้คูปองลดราคา y %',
            self::TYPE_THING_GET_COUPON => '05.4 - ซื้อครบ x เล่ม สามารถใช้คูปองลดราคา y บาท',
            self::TYPE_PRICE_GET_THING => '06 - ซื้อครบ x บาท แถมฟรี y เล่ม',
            self::TYPE_GIFT => '07 - ซื้อครบ x บาท แถมของที่ระลึก',
            self::TYPE_THING_GET_DISCOUNT_P => '08.1 - ซื้อครบ x เล่ม ลดราคา y %',
            self::TYPE_PRICE_GET_DISCOUNT_2 => '08.2 - ซื้อครบ x บาท ลดราคา y บาท',
            self::TYPE_THING_GET_DISCOUNT => '09.1 - ซื้อครบ x เล่ม ลดราคา y บาท',
            self::TYPE_PRICE_GET_DISCOUNT_P_2 => '09.2 - ซื้อครบ x บาท ลดราคา y %',
            self::TYPE_THING_GET_DISCOUNT_A => '10 - ซื้อครบ x เล่ม จ่ายเล่มแพงสุด',
            self::TYPE_THING_GET_THING => '11 - ซื้อครบ x เล่ม แถมฟรี y เล่ม',
            self::TYPE_GIFT_BY_THING => '12 - ซื้อครบ x เล่ม แถมของที่ระลึก',
            self::TYPE_BUFFET => '13 - บุฟเฟต์',
            self::TYPE_COUPON_P => '14 - คูปอง ลดราคา y %',
            self::TYPE_COUPON => '15 - คูปอง ลดราคา y บาท',
            self::TYPE_FREE_DELIVERY => '16 - ฟรีค่าจัดส่ง',
            self::TYPE_KEY_TO_DISCOUNT => '17 - ซื้อ A เพื่อลดราคา y บาท ในเล่มอื่นๆ',
            self::TYPE_KEY_TO_DISCOUNT_P => '18 - ซื้อ A เพื่อลดราคา y % ในเล่มอื่นๆ',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public static function find() {
        return Yii::createObject(PromotionQuery::className(), [get_called_class()]);
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อโปรโมชั่น',
            'date_start' => 'วันเริ่ม',
            'date_end' => 'วันที่สิ้นสุด',
            'is_active' => 'เปิดใช้งาน',
            'promotion_type' => 'ประเภท',
            'status' => 'สถานะ',
            'is_once' => 'ข้ามส่วนลดประเภทเดียวกัน',
            'is_final' => 'จบการคำนวนส่วนลดทันที',
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

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->order_no = Yii::$app->db->createCommand('SELECT COALESCE(MAX(order_no),0)+1 as position FROM promotion')->queryScalar();
            }
            return true;
        }
    }

    public function beforeDelete() {
        if (parent::beforeDelete()) {
            PromotionProduct::deleteAll([
                'promotion_id' => $this->id,
            ]);
            PromotionItem::deleteAll([
                'promotion_id' => $this->id,
            ]);
            PromotionCoupon::deleteAll([
                'promotion_id' => $this->id,
            ]);
            PromotionGift::deleteAll([
                'promotion_id' => $this->id,
            ]);
            return true;
        }
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['name', 'promotion_type', 'date_start', 'date_end'], 'required'];
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterWhere(['LIKE', 'name', ArrayHelper::getValue($this->search, 'text')]);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'is_active' => SORT_DESC,
                    'order_no' => SORT_ASC,
                ],
            ],
        ]);
    }

    public function getIsExpired() {
        $time = time();
        if ($time < strtotime($this->date_start) && $time > strtotime($this->date_end)) {
            return true;
        }
        return false;
    }

    public function getStatus() {
        $time = time();
        if ($time < strtotime($this->date_start)) {
            return Html::tag('span', 'ยังไม่ถึงกำหนด');
        }

        if ($time > strtotime($this->date_end)) {
            return Html::tag('span', 'หมดเขต');
        }

        if ($this->is_active) {
            return Html::tag('span', 'กำลังใช้งาน');
        }

        return Html::tag('span', '-');
    }

    public function saveCategoryItems($items) {
        $this->clearCategoeryItems();
        $data = [];
        foreach ($items as $item) {
            $categoryItem = Folder::findOne($item);
            $data[] = [
                $this->id,
                $categoryItem->id,
            ];
        }
        if (count($data)) {
            return $this->getDb()->createCommand()->batchInsert('promotion_folder', ['promotion_id', 'folder_id'], $data)->execute();
        }
        return false;
    }

    public function clearCategoeryItems() {
        PromotionFolder::deleteAll([
            'promotion_id' => $this->id,
        ]);
    }

    public function getCategoryItems() {
        return $this->hasMany(PromotionFolder::className(), ['promotion_id' => 'id']);
    }

    public function doMoveUp() {
        $target = Promotion::find()
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['<', 'order_no', $this->order_no])
                ->orderBy([
                    'ABS(order_no - :order_no)' => SORT_ASC
                ])
                ->params([
                    ':order_no' => $this->order_no,
                ])
                ->one();
        if (isset($target)) {
            $targetPosition = $target->order_no;
            $target->updateAttributes([
                'order_no' => $this->order_no,
            ]);
            $this->updateAttributes([
                'order_no' => $targetPosition,
            ]);
        }
    }

    public function doMoveDown() {
        $target = Promotion::find()
                ->andWhere(['<>', 'id', $this->id])
                ->andWhere(['>', 'order_no', $this->order_no])
                ->orderBy([
                    'ABS(:order_no - order_no)' => SORT_ASC
                ])
                ->params([
                    ':order_no' => $this->order_no,
                ])
                ->one();
        if (isset($target)) {
            $targetPosition = $target->order_no;
            $target->updateAttributes([
                'order_no' => $this->order_no,
            ]);
            $this->updateAttributes([
                'order_no' => $targetPosition,
            ]);
        }
    }

    public function productAdd($items) {
        $keys = array_keys($this->getPromotionProducts()->asArray()->indexBy('product_id')->all());
        $items = array_diff($items, $keys);

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [$this->id, $item];
        }
        $count = $this->getDb()->createCommand()->batchInsert('promotion_product', ['promotion_id', 'product_id'], $rows)->execute();
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function productRemove($items) {
        $count = PromotionProduct::deleteAll([
                    'promotion_id' => $this->id,
                    'product_id' => $items,
        ]);
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function itemAdd($items) {
        $keys = array_keys($this->getPromotionItems()->asArray()->indexBy('product_id')->all());
        $items = array_diff($items, $keys);

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [$this->id, $item];
        }
        $count = $this->getDb()->createCommand()->batchInsert('promotion_item', ['promotion_id', 'product_id'], $rows)->execute();
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function itemRemove($items) {
        $count = PromotionItem::deleteAll([
                    'product_id' => $items,
        ]);
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function giftAdd($items) {
        $keys = array_keys($this->getPromotionGifts()->asArray()->indexBy('gift_id')->all());
        $items = array_diff($items, $keys);

        $rows = [];
        foreach ($items as $item) {
            $rows[] = [$this->id, $item];
        }
        $count = $this->getDb()->createCommand()->batchInsert('promotion_gift', ['promotion_id', 'gift_id'], $rows)->execute();
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function giftRemove($items) {
        $count = PromotionGift::deleteAll([
                    'gift_id' => $items,
        ]);
        return [
            'result' => 'success',
            'itemCount' => $count,
        ];
    }

    public function getPromotionManager() {
        switch ($this->promotion_type) {
            case self::TYPE_DISCOUNT:
                return new PromotionDiscountManager([
                    'model' => $this,
                ]);
            case self::TYPE_DISCOUNT_FIX:
                $model = new PromotionDiscountManager([
                    'model' => $this,
                ]);
                $model->discount_type = '$';
                return $model;
            case self::TYPE_BIRTH:
                return new PromotionBirthManager([
                    'model' => $this,
                    'data' => [
                        'user' => Yii::$app->user,
                    ],
                ]);
            case self::TYPE_PRICE_GET_DISCOUNT_P:
            case self::TYPE_PRICE_GET_DISCOUNT_P_2:
                $model = new PromotionCartDiscountManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'total';
                $model->discount_type = '%';
                return $model;
            case self::TYPE_PRICE_GET_DISCOUNT:
            case self::TYPE_PRICE_GET_DISCOUNT_2:
                $model = new PromotionCartDiscountManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'total';
                $model->discount_type = '$';
                return $model;
            case self::TYPE_PRICE_GET_COUPON_P:
                $model = new PromotionCartCouponManager([
                    'model' => $this,
                ]);
                $model->discount_type = '%';
                $model->buy_type = 'total';
                return $model;
            case self::TYPE_THING_GET_COUPON_P:
                $model = new PromotionCartCouponManager([
                    'model' => $this,
                ]);
                $model->discount_type = '%';
                $model->buy_type = 'amount';
                return $model;
            case self::TYPE_PRICE_GET_COUPON:
                $model = new PromotionCartCouponManager([
                    'model' => $this,
                ]);
                $model->discount_type = '$';
                $model->buy_type = 'total';
                return $model;
            case self::TYPE_THING_GET_COUPON:
                $model = new PromotionCartCouponManager([
                    'model' => $this,
                ]);
                $model->discount_type = '$';
                $model->buy_type = 'amount';
                return $model;
            case self::TYPE_PRICE_GET_THING:
                $model = new PromotionCartProductManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'total';
                return $model;
            case self::TYPE_THING_GET_THING:
                $model = new PromotionCartProductManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'amount';
                return $model;
            case self::TYPE_THING_GET_DISCOUNT_P:
                $model = new PromotionCartDiscountManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'amount';
                $model->discount_type = '%';
                return $model;
            case self::TYPE_THING_GET_DISCOUNT:
                $model = new PromotionCartDiscountManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'amount';
                $model->discount_type = '$';
                return $model;
            case self::TYPE_THING_GET_DISCOUNT_A:
                $model = new PromotionCartPayOneManager([
                    'model' => $this,
                ]);
                return $model;
            case self::TYPE_GIFT:
                $model = new PromotionCartGiftManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'total';
                return $model;
            case self::TYPE_GIFT_BY_THING:
                $model = new PromotionCartGiftManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'amount';
                return $model;
            case self::TYPE_BUFFET:
                $model = new PromotionCartBuffetManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'amount';
                return $model;
            case self::TYPE_COUPON_P:
                $model = new PromotionCouponManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'amount';
                $model->discount_type = '%';
                return $model;
            case self::TYPE_COUPON:
                $model = new PromotionCouponManager([
                    'model' => $this,
                ]);
                $model->buy_type = 'amount';
                $model->discount_type = '$';
                return $model;
            case self::TYPE_FREE_DELIVERY:
                $model = new PromotionCartFreeDeliveryManager([
                    'model' => $this,
                ]);
                return $model;
            case self::TYPE_KEY_TO_DISCOUNT:
                $model = new PromotionCartProductKeyManager([
                    'model' => $this,
                ]);
                $model->discount_type = '$';
                //$model->buy_type = 'single';
                return $model;
            case self::TYPE_KEY_TO_DISCOUNT_P:
                $model = new PromotionCartProductKeyManager([
                    'model' => $this,
                ]);
                $model->discount_type = '%';
                //$model->buy_type = 'single';
                return $model;
        }
        return new PromotionGeneralManager([
            'model' => $this,
        ]);
    }

    public function getPromotionCoupons() {
        return $this->hasMany(PromotionCoupon::className(), ['promotion_id' => 'id']);
    }

}

class PromotionQuery extends ActiveQuery {

    public function current() {
        $this->andWhere([
            'is_active' => 1,
        ]);
        return $this;
    }

    public function active($date = null) {
        $date = isset($date) ? $date : date('Y-m-d H:i:s');
        $this->andWhere([
            'is_active' => 1,
        ]);
        $this->andWhere(['<=', 'date_start', $date]);
        $this->andWhere(['>=', 'date_end', $date]);
        return $this;
    }

    public function beforeCart() {
        $this->andWhere([
            'promotion_type' => [
                Promotion::TYPE_DISCOUNT,
                Promotion::TYPE_DISCOUNT_FIX,
                Promotion::TYPE_BIRTH,
            ],
        ]);
        return $this;
    }

    public function afterCart() {
        $this->andWhere([
            'NOT IN',
            'promotion_type', [
                Promotion::TYPE_DISCOUNT,
                Promotion::TYPE_DISCOUNT_FIX,
                Promotion::TYPE_BIRTH,
            ],
        ]);
        return $this;
    }

}
