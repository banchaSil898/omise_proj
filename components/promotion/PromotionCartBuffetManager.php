<?php

namespace app\components\promotion;

class PromotionCartBuffetManager extends PromotionCartProductManager {

    /**
     * No need to discount.
     * @param type $price
     * @return type
     */
    public function processPrice($price) {
        $fullPrice = $this->getValidItemsPrice();
        return $price - $fullPrice + $this->buy_amount;
    }

    public function attributeLabels() {
        return [
            'buy_amount' => 'ราคาเหมาบุฟเฟต์ (บาท)',
            'product_count' => 'จำนวนสินค้า',
        ];
    }

    public function rules() {
        $rules = [];
        $rules[] = [['buy_amount', 'product_count'], 'required'];
        $rules[] = [['buy_amount'], 'number', 'min' => 0];
        $rules[] = [['product_count'], 'integer', 'min' => 0];
        return $rules;
    }

    public function isValid($cart = null, $afterPrice = null) {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionProduct($id)) {
                $total = $total + $data['amount'];
            }
        }
        return $total <= $this->product_count;
    }

}
