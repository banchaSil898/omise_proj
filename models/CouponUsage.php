<?php

namespace app\models;

use app\models\base\CouponUsage as BaseCouponUsage;

class CouponUsage extends BaseCouponUsage {

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

}
