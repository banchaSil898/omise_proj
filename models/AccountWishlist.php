<?php

namespace app\models;

use app\models\base\AccountWishlist as BaseAccountWishlist;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class AccountWishlist extends BaseAccountWishlist {

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'created_at' => 'วันที่เพิ่ม',
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

    public function getAccount() {
        return $this->hasOne(Account::className(), ['id' => 'account_id']);
    }

    public function getProduct() {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('product_id', $this->product_id);
        $query->andFilterCompare('account_id', $this->account_id);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
