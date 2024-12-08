<?php

namespace app\components\promotion;

use app\models\Product;
use app\models\PromotionItem;

class PromotionCartProductManager extends PromotionCartDiscountManager {

    public $product_count;

    /**
     * No need to discount.
     * @param type $price
     * @return type
     */
    public function processPrice($price) {
        return $price;
    }

    public function attributeLabels() {
        return [
            'buy_amount' => $this->buy_type === 'total' ? 'เมื่อซื้อครบ (บาท)' : 'จำนวนที่ซื้อ (เล่ม)',
            'product_count' => 'จำนวนสินค้าที่แถม',
        ];
    }

    public function attributeInputs() {
        return [
            'buy_amount' => [
                'type' => 'text',
            ],
            'product_count' => [
                'type' => 'text',
            ],
        ];
    }

    public function rules() {
        $rules = [];
        $rules[] = [['buy_amount', 'product_count'], 'required'];
        $rules[] = [['buy_amount'], 'number', 'min' => 0];
        $rules[] = [['product_count'], 'integer', 'min' => 0];
        return $rules;
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

    public function getAvailableProducts($afterPrice = null) {
        if ($this->buy_type == 'total' && isset($afterPrice)) {
            $total = $this->getTotalItems();
            $total = $total < $afterPrice ? $total : $afterPrice;
        } else {
            $total = $this->getTotalItems();
        }

        $ids = PromotionItem::find()
                ->andWhere(['promotion_id' => $this->model->id])
                ->asArray()
                ->indexBy('product_id')
                ->all();
        return Product::find()->where(['IN', 'id', array_keys($ids)])->isStockAvailable()->all();
    }

}
