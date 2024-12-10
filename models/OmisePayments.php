<?php
namespace app\models;

use app\models\base\OmisePayments as BaseOmisePayments;
use Exception;
use Yii;
use yii\base\InvalidArgumentException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

class OmisePayments extends BaseOmisePayments{
  public static function find() {
    return Yii::createObject(OmisePaymentsQuery::className(), [get_called_class()]);
  }
}

class OmisePaymentsQuery extends ActiveQuery{
  
}