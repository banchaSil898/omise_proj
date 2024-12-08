<?php

namespace app\models;

use app\models\base\Bank as BaseBank;
use Yii;
use yii\db\ActiveQuery;

class Bank extends BaseBank {

    public static function find() {
        return Yii::createObject(BankQuery::className(), [get_called_class()]);
    }

    public function getShortName() {
        $ret = [];
        $ret[] = $this->name;
        $ret[] = '(' . $this->account_no . ')';
        return implode(' ', $ret);
    }

}

class BankQuery extends ActiveQuery {

    public function active() {
        $this->andFilterWhere(['is_enabled' => 1]);
        $this->orderBy(['order_no' => SORT_ASC]);
        return $this;
    }

}
