<?php

namespace app\models;

use app\models\base\WordConvert as BaseWordConvert;
use Yii;
use yii\data\ActiveDataProvider;

class WordConvert extends BaseWordConvert {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'word_from' => 'แปลงจาก',
            'word_to' => 'แปลงเป็น',
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
