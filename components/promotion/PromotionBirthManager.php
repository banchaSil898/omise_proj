<?php

namespace app\components\promotion;

class PromotionBirthManager extends PromotionDiscountManager {

    public function isValid($cart = null, $afterPrice = null) {
        $user = $this->data['user'];
        if ($user->isGuest) {
            return false;
        }
        $model = $user->identity;
        if (date('n') == date('n', strtotime($model->birth_date))) {
            return true;
        }
    }

}
