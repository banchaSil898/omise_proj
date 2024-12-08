<?php

namespace app\models;

use app\models\base\Country as BaseCountry;
use Yii;
use yii\db\ActiveQuery;

class Country extends BaseCountry {

    public static function find() {
        return Yii::createObject(CountryQuery::className(), [get_called_class()]);
    }

    public function getName() {
        return $this->iso_alpha3 . ' : ' . $this->country_name;
    }

    public function getLocName() {
        if ($this->country_code <> 'TH') {
            return ' , ' . $this->country_name;
        }
        return '';
    }

}

class CountryQuery extends ActiveQuery {

    public function scopeExistInRate() {
        $exists = array_keys(DeliveryRate::find()->select('country_id')->groupBy('country_id')->asArray()->indexBy('country_id')->all());
        $this->andWhere(['iso_alpha3' => $exists]);
        return $this;
    }

}
