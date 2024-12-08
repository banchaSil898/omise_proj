<?php

namespace app\models;

use app\models\base\CouponGroup as BaseCouponGroup;
use yii\data\ActiveDataProvider;

class CouponGroup extends BaseCouponGroup {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อชุด',
            'code_prefix' => 'Prefix',
            'code_suffix' => 'Suffix',
            'coupon_count' => 'จำนวนคูปอง',
            'valid_date' => 'วันที่ใช้งานได้',
            'expire_date' => 'วันหมดอายุ',
            'usage_max' => 'จำนวน',
            'discount_type' => 'ประเภทส่วนลด',
            'discount_value' => 'ส่วนลด',
        ]);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['name', 'coupon_count', 'valid_date', 'expire_date', 'discount_type', 'discount_value'], 'required'];
        $rules[] = [['coupon_count'], 'integer', 'min' => 1];
        $rules[] = [['search'], 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
