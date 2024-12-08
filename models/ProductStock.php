<?php

namespace app\models;

use app\models\base\ProductStock as BaseProductStock;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class ProductStock extends BaseProductStock {

    public $search;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'base_price' => 'ราคาต้นทุน',
            'amount' => 'จำนวน',
            'description' => 'หมายเหตุ',
            'created_at' => 'วันที่ทำรายการ',
            'amount_new' => 'คงเหลือ',
        ]);
    }

    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            $product = $this->product;
            $this->amount_old = $product->stock;
            $this->amount_new = $product->stock + $this->amount;
            return true;
        }
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        $product = $this->product;
        $product->updateAttributes([
            'stock' => $this->amount_new,
            'stock_est' => $product->stock_est + $this->amount,
        ]);
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('product_id', $this->product_id);
        return new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);
    }

}
