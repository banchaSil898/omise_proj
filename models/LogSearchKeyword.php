<?php

namespace app\models;

use app\models\base\LogSearchKeyword as BaseLogSearchKeyword;
use yii\data\ActiveDataProvider;

class LogSearchKeyword extends BaseLogSearchKeyword {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'keyword' => 'คำค้น',
            'result_count' => 'ผลลัพธ์',
            'result_time' => 'จำนวนที่ใช้',
        ]);
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

}
