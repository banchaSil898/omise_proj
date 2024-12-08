<?php

namespace app\models;

use app\models\base\ProductImage as BaseProductImage;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class ProductImage extends BaseProductImage {

    public $search;
    public $thumb_width = 128;
    public $thumb_height = 128;

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'thumb_url' => 'ภาพตัวอย่าง',
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

    public function beforeValidate() {
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                $this->order_no = Yii::$app->db->createCommand('SELECT COALESCE(MAX(order_no),0)+1 as order_no FROM product_image WHERE product_id = :product_id')->bindValue(':product_id', $this->product_id)->queryScalar();
                $this->image_id = Yii::$app->db->createCommand('SELECT COALESCE(MAX(image_id),0)+1 as image_id FROM product_image WHERE product_id = :product_id')->bindValue(':product_id', $this->product_id)->queryScalar();
            }
            return true;
        }
    }

    public function getImageUrl() {
        return Yii::getAlias($this->img_url);
    }

    public function getThumbUrl() {
        return Yii::getAlias($this->thumb_url);
    }

    public function search() {
        $query = $this->find();
        $query->andFilterCompare('product_id', $this->product_id);
        return new ActiveDataProvider([
            'query' => $query,
        ]);
    }

}
