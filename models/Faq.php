<?php

namespace app\models;

use app\models\base\Faq as BaseFaq;
use yii\data\ActiveDataProvider;

class Faq extends BaseFaq {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'คำถาม',
            'answer' => 'คำตอบ',
        ]);
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
