<?php

namespace app\components\promotion;

class PromotionCartCouponManager extends PromotionCartDiscountManager {

    public $coupon_code;
    public $discount_type;

    /**
     * No need to discount.
     * @param type $price
     * @return type
     */
    public function processPrice($price) {
        $dPrice = $this->getValidItemsPrice();

        switch ($this->discount_type) {
            case '%':
                $afterPrice = ceil($dPrice - ($dPrice * ($this->discount_value / 100)));
                break;
            default:
                $afterPrice = $dPrice - $this->discount_value;
                break;
        }
        return $afterPrice + ($price - $dPrice);
    }

    public function isValid($cart = null, $afterPrice = null) {
        $coupon = $cart->coupon;
        if (!isset($coupon)) {
            return false;
        }
        $total = $this->getTotalItems($cart);
        if ($total >= $this->buy_amount) {
            if (trim($this->coupon_code) === trim($coupon->code)) {
                return true;
            }
        }
        return false;
    }

    public function attributeLabels() {
        return [
            'buy_amount' => 'เมื่อซื้อครบ (บาท)',
            'coupon_code' => 'รหัสคูปอง',
            'discount_value' => 'ส่วนลด (' . ($this->discount_type == '%' ? '%' : 'บาท') . ')',
        ];
    }

    public function attributeInputs() {
        return [
            'buy_amount' => [
                'type' => 'text',
            ],
            'coupon_code' => [
                'type' => 'text',
            ],
            'discount_value' => [
                'type' => 'text',
            ],
        ];
    }

    public function rules() {
        $rules = [];
        $rules[] = [['buy_amount', 'coupon_code', 'discount_value'], 'required'];
        $rules[] = [['buy_amount', 'discount_value'], 'number', 'min' => 0];
        return $rules;
    }

    public function getHtmlCondition() {
        $html = [];
        $html[] = '<div>';
        $html[] = $this->buy_type === 'total' ? 'เมื่อซื้อครบ ' . $this->buy_amount . ' บาท' : 'จำนวนที่ซื้อ ' . $this->buy_amount . ' เล่ม';
        $html[] = ' : ลด ' . $this->discount_value . ($this->discount_type === '%' ? '%' : '฿');
        $html[] = '</div>';
        return implode('', $html);
    }

}
