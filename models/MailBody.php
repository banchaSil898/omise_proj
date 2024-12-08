<?php

namespace app\models;

use app\models\base\MailBody as BaseMailBody;
use yii\data\ActiveDataProvider;

class MailBody extends BaseMailBody {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'mail_title' => 'หัวเรื่อง',
            'mail_body' => 'เนื้อหา',
        ]);
    }

    public function rules() {
        $rules = parent::rules();
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
