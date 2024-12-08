<?php

namespace app\models;

use app\models\base\Coupon as BaseCoupon;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class Coupon extends BaseCoupon {

    const TYPE_PERCENT = 1;
    const TYPE_VALUE = 2;

    public $search;

    public static function getDiscountTypeOptions($code = null) {
        $ret = [
            self::TYPE_PERCENT => 'ลดราคาเป็นร้อยละ (%)',
            self::TYPE_VALUE => 'ลดราคาเป็นจำนวน (บาท)',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'code' => 'รหัสส่วนลด',
            'valid_date' => 'วันที่ใช้งานได้',
            'expire_date' => 'วันหมดอายุ',
            'usage_max' => 'จำนวน',
            'usage_current' => 'ใช้ไปแล้ว',
            'discount_type' => 'ประเภทส่วนลด',
            'discount_value' => 'ส่วนลด',
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

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['search'], 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    public function getIsUsable() {
        if ($this->valid_date && $this->valid_date >= date('Y-m-d')) {
            return false;
        }
        if ($this->expire_date && $this->expire_date <= date('Y-m-d')) {
            return false;
        }
        if ($this->usage_current >= $this->usage_max) {
            return false;
        }
        return true;
    }

}
