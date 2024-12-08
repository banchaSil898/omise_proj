<?php

namespace app\models;

use app\models\base\PromotionCouponUsage as BasePromotionCouponUsage;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

class PromotionCouponUsage extends BasePromotionCouponUsage {

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

}
