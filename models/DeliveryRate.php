<?php

namespace app\models;

use app\models\base\DeliveryRate as BaseDeliveryRate;
use yii\data\ActiveDataProvider;

class DeliveryRate extends BaseDeliveryRate {

    const TYPE_EMS = '3';
    const TYPE_CUSTOM = '6';

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'weight' => 'น้ำหนัก',
            'fee' => 'ราคา',
            'country_id' => 'ประเทศ',
        ]);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = ['country_id', 'safe', 'on' => 'search'];
        return $rules;
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('delivery_id', $this->delivery_id);
        $query->andFilterCompare('country_id', $this->country_id);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
