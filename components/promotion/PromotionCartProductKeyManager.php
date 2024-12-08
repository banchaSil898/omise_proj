<?php

namespace app\components\promotion;

use yii\helpers\Html;

class PromotionCartProductKeyManager extends PromotionCartProductManager {

    public $product_count;

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
                $afterPrice = $dPrice - ($this->getValidItemsCount() * $this->discount_value);
                break;
        }
        return $afterPrice + ($price - $dPrice);
    }

    public function attributeLabels() {
        return [
            'discount_value' => 'ส่วนลด (' . ($this->discount_type == '%' ? '%' : 'บาท') . ')',
        ];
    }

    public function attributeInputs() {
        return [
            'discount_value' => [
                'type' => 'text',
            ],
        ];
    }

    public function rules() {
        $rules = [];
        $rules[] = [['discount_value'], 'required'];
        $rules[] = [['discount_value'], 'number', 'min' => 0, 'max' => 100, 'when' => function($model) {
                return $model->discount_type == '%';
            }];
        $rules[] = [['discount_value'], 'number', 'min' => 0, 'when' => function($model) {
                return $model->discount_type == '$';
            }];
        return $rules;
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

    public function getValidItemsPrice() {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionItem($id)) {
                $total += $data['total'];
            }
        }
        return $total;
    }

    public function getValidItemsCount() {
        $cart = $this->getCart();
        $total = 0;
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionItem($id)) {
                $total = $total + $data['amount'];
            }
        }
        return $total;
    }

    public function getPromotionDetail() {
        $html = [];
        $cart = $this->getCart();
        foreach ($cart->items as $id => $data) {
            if ($this->getIsPromotionItem($id)) {
                $html[] = Html::tag('li', Html::tag('small','• '. $data['model']->name),['style' => 'list-style:none;']);
            }
        }
        return Html::beginTag('ul') . implode('', $html) . Html::endTag('ul');
    }

}
