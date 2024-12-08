<?php

namespace app\components\promotion;

use app\components\Cart;
use app\models\PromotionCoupon;
use app\models\PromotionCouponUsage;
use app\models\Purchase;

class PromotionCouponManager extends PromotionCartDiscountManager {

    /**
     * 
     * @param string $code
     * @return PromotionCoupon
     */
    public function getCouponByCode($code) {
        return $this->getModel()->getPromotionCoupons()->where([
                    'code' => $code,
                ])->one();
    }

    public function isValid($cart = null, $afterPrice = null) {
        if (!isset($cart->coupon)) {
            return false;
        }
        $total = 0;
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionProduct($id)) {
                $total += $data[$this->buy_type];
            }
            if ($total >= $this->buy_amount) {
                $coupon = $this->getCouponByCode($cart->coupon->code);
                if (isset($coupon) && $coupon->isUsable) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getPromotionName() {
        $code = isset($this->getCart()->coupon->code) ? '(' . $this->getCart()->coupon->code . ')' : '';
        return $this->getModel()->name . ' ' . $code;
    }

    public function afterCheckout(Purchase $purchase, $discount = 0) {
        $coupon = $this->getCart()->coupon;
        $coupon->updateAttributes([
            'usage_current' => $coupon->usage_current + 1,
            'is_used' => (($coupon->usage_current + 1) >= $coupon->usage_max),
        ]);

        $usage = new PromotionCouponUsage;
        $usage->promotion_coupon_id = $coupon->id;
        $usage->purchase_id = $purchase->id;
        $usage->promotion_id = $this->getModel()->id;
        $usage->discount_amount = $discount;
        $usage->member_id = $purchase->member_id;
        $usage->save();
        return true;
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
