<?php

namespace app\models;

use app\models\base\Delivery as BaseDelivery;
use yii\data\ActiveDataProvider;

class Delivery extends BaseDelivery {

    const FEE_STATIC = 0;
    const FEE_WEIGHT = 1;

    public $search;

    public static function getFeeTypeOptions($code = null) {
        $ret = [
            self::FEE_STATIC => 'อัตราคงที่',
            self::FEE_WEIGHT => 'คิดตามน้ำหนักสินค้า',
        ];
        return isset($code) ? $ret[$code] : $ret;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อ',
            'fee' => 'ค่าธรรมเนียม',
            'fee_type' => 'ประเภทค่าธรรมเนียม',
            'condition' => 'เงื่อนไข',
        ]);
    }

    public function rules() {
        $rules = parent::rules();

        $rules[] = [['fee_type'], 'required'];
        $rules[] = [['fee'], 'required', 'when' => function($model) {
                return $model->fee_type == Delivery::FEE_STATIC;
            }];
        $rules[] = ['search', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
