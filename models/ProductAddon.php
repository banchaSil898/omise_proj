<?php

namespace app\models;

use app\models\base\ProductAddon as BaseProductAddon;

class ProductAddon extends BaseProductAddon {

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'name' => 'ชื่อ',
            'price' => 'ราคาที่บวกเพิ่ม',
        ]);
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = [['name', 'price'], 'required'];
        $rules[] = ['price', 'number', 'min' => 0];
        return $rules;
    }

    public function getProduct() {
        return $this->hasOne(Product::class, ['id' => 'product_id']);
    }

    public function getPriceSell() {
        return ($this->product->currentPrice + $this->price);
    }

}
