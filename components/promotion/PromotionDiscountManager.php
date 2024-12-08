<?php

namespace app\components\promotion;

use app\components\promotion\PromotionGeneralManager;
use app\models\PromotionItem;
use app\models\PromotionProduct;

class PromotionDiscountManager extends PromotionGeneralManager {

    public $type = 'beforeCart';
    public $discount_type = '%';
    public $discount_value;
    public $discount_max;
    public $isOnce = false;
    public $product_id;

    public function processPrice($price) {
        if ($this->getIsPromotionProduct($this->product_id)) {
            switch ($this->discount_type) {
                case '%':
                    $discount = ($price * ($this->discount_value / 100));
                    if (!empty($this->discount_max) && $discount > $this->discount_max) {
                        $discount = $this->discount_max;
                    }
                    return ceil($price - $discount);
                default:
                    $discount = $this->discount_value;
                    if (!empty($this->discount_max) && $this->discount_value > $this->discount_max) {
                        $discount = $this->discount_max;
                    }
                    return $discount;
            }
        }
        return $price;
    }

    public function isValid($cart = null, $afterPrice = null) {
        return $this->getIsPromotionProduct($this->product_id);
    }

    public function getIsPromotionProduct($id) {
        return boolval(PromotionProduct::findOne([
                    'promotion_id' => $this->model->id,
                    'product_id' => $id,
        ]));
    }

    public function getIsPromotionItem($id) {
        return boolval(PromotionItem::findOne([
                    'promotion_id' => $this->model->id,
                    'product_id' => $id,
        ]));
    }

    public function attributeLabels() {
        return [
            'discount_value' => 'ส่วนลด (' . ($this->discount_type == '%' ? '%' : 'บาท') . ')',
            'discount_max' => 'ลดสูงสุด (บาท)',
        ];
    }

    public function attributeInputs() {
        $ret = [];
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
        $rules[] = ['discount_value', 'required'];
        $rules[] = ['discount_value', 'number', 'min' => 0];
        $rules[] = ['discount_max', 'safe'];
        return $rules;
    }

}
