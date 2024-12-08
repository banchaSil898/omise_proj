<?php

namespace app\components\promotion;

class PromotionCartFreeDeliveryManager extends PromotionDiscountManager {

    public $type = 'afterCart';
    public $showOnCart = false;

    public function attributeInputs() {
        return [
        ];
    }

    public function isValid($cart = null, $afterPrice = null) {
        $cart = $this->getCart();
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionProduct($id)) {
                return true;
            }
        }
        return false;
    }

}
