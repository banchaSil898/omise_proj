<?php

namespace app\components\promotion;

class PromotionCartPayOneManager extends PromotionCartDiscountManager {

    public $buy_type = 'amount';
    public $include_count;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'include_count' => 'จำนวนที่จะคิดเงิน (เล่ม)',
        ]);
    }

    public function attributeInputs() {
        return [
            'buy_amount' => [
                'type' => 'text',
            ],
            'include_count' => [
                'type' => 'text',
            ],
        ];
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = ['include_count', 'required'];
        $rules[] = ['include_count', 'integer', 'min' => 1];
        $rules[] = ['include_count', 'compare', 'operator' => '<', 'compareAttribute' => 'buy_amount'];
        return $rules;
    }

    public function isValid($cart = null, $afterPrice = null) {
        $total = $this->getTotalItems($cart);
        if ($total == $this->buy_amount) {
            return true;
        }
        return false;
    }

    public function processPrice($price) {
        $cart = $this->getCart();
        $total = 0;
        $items = [];
        $discount = [];
        if ($this->include_count) {
            foreach ($cart->items as $id => $data) {
                if ($this->getIsPromotionProduct($id)) {
                    for ($i = 0; $i < $data['amount']; $i++) {
                        $items[] = $data['price'];
                    }
                }
            }

            rsort($items);
            for ($i = 0; $i < $this->include_count; $i++) {
                $discount[] = $items[$i];
            }

            return $price - (array_sum($items) - array_sum($discount));
        }
        return $price;
    }

}
