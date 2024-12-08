<?php

namespace app\models;

use app\models\base\PurchaseProduct as BasePurchaseProduct;
use yii\data\ActiveDataProvider;

class PurchaseProduct extends BasePurchaseProduct {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
        ]);
    }

    public function search() {
        $query = $this->find();
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase() {
        return $this->hasOne(Purchase::className(), ['id' => 'purchase_id']);
    }

}
