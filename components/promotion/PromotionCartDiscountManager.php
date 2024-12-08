<?php

namespace app\components\promotion;

use app\components\promotion\PromotionDiscountManager;

class PromotionCartDiscountManager extends PromotionDiscountManager {

    public $type = 'afterCart';
    public $buy_type = 'total';
    public $discount_type = '%';
    public $buy_amount = 0;

    public function processPrice($price) {
        $dPrice = $this->getValidItemsPrice();
        switch ($this->discount_type) {
            case '%':
                $discount = ($dPrice * ($this->discount_value / 100));
                if (!empty($this->discount_max) && $discount > $this->discount_max) {
                    $discount = $this->discount_max;
                }
                $afterPrice = ceil($dPrice - $discount);
                break;
            default:
                if (!empty($this->discount_max) && $this->discount_value > $this->discount_max) {
                    $discount = $this->discount_max;
                }else{
			$discount = $this->discount_value;
		}
                $afterPrice = $dPrice - $discount;
                break;
        }
        return $afterPrice + ($price - $dPrice);
    }

    public function isValid($cart = null, $afterPrice = null) {
        $total = $this->getTotalItems($cart);
        if ($total >= $this->buy_amount) {
            return true;
        }
        return false;
    }

    public function attributeLabels() {
        return [
            'buy_amount' => $this->buy_type === 'total' ? 'เมื่อซื้อครบ (บาท)' : 'จำนวนที่ซื้อ (เล่ม)',
            'discount_value' => 'ส่วนลด (' . ($this->discount_type == '%' ? '%' : 'บาท') . ')',
            'discount_max' => 'ลดสูงสุด (บาท)',
        ];
    }

    public function attributeInputs() {
        $ret = [];
        $ret['buy_amount'] = [
            'type' => 'text',
        ];
        $ret['discount_value'] = [
            'type' => 'text',
        ];
        if ($this->discount_type == '%') {
            $ret['discount_max'] = [
                'type' => 'text',
            ];
        }
        return $ret;
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['buy_amount', 'discount_value'], 'required'];
        $rules[] = [['buy_amount', 'discount_value'], 'number', 'min' => 0];
        return $rules;
    }

    public function getTotalItems() {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionProduct($id)) {
                $total += $data[$this->buy_type];
            }
        }
        return $total;
    }

    public function getValidItemsPrice() {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionProduct($id)) {
                $total += $data['total'];
            }
        }
        return $total;
    }

}
