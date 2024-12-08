<?php

namespace app\models;

use app\models\base\ContactType as BaseContactType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ContactType extends BaseContactType {

    public static function find() {
        return Yii::createObject(ContactTypeQuery::className(), [get_called_class()]);
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}

class ContactTypeQuery extends ActiveQuery {

    public function scopeDefault() {
        return $this;
    }

}
