<?php

namespace app\components\promotion;

use app\components\promotion\PromotionCartDiscountManager;
use app\components\promotion\PromotionGiftDetail;
use app\models\Gift;
use app\models\PromotionGift;
use Yii;
use yii\helpers\ArrayHelper;

class PromotionCartGiftManager extends PromotionCartDiscountManager {

    public $type = 'afterCart';
    public $buy_type = 'total';
    public $discount_type = '%';
    public $buy_amount = 0;

    public function processPrice($price) {
        return $price;
    }

    public function attributeLabels() {
        return [];
    }

    public function attributeInputs() {
        return [];
    }

    public function isValid($cart = null, $afterPrice = null) {
        $gifts = $this->getAvailableGifts($afterPrice);
        return count($gifts);
    }

    public function getAvailableGifts($afterPrice = null) {
        if ($this->buy_type == 'total' && isset($afterPrice)) {
            $total = $this->getTotalItems();
            $total = $total < $afterPrice ? $total : $afterPrice;
        } else {
            $total = $this->getTotalItems();
        }
        $max_rate = PromotionGift::find()
                ->select('MAX(buy_rate)')
                ->andWhere(['promotion_id' => $this->model->id])
                ->andWhere(['<=', 'buy_rate', $total])
                ->orderBy(['buy_rate' => SORT_DESC])
                ->scalar();
        if ($max_rate) {
            $ids = PromotionGift::find()
                    ->andWhere(['promotion_id' => $this->model->id])
                    ->andWhere(['buy_rate' => $max_rate])
                    ->asArray()
                    ->indexBy('gift_id')
                    ->all();
            return Gift::find()->where(['IN', 'id', array_keys($ids)])->andWhere(['>', 'stock_est', '0'])->all();
        }
        return [];
    }

    public function getGift() {
        $gift = $this->getValidGift();
        if (isset($gift)) {
            $giftModel = new PromotionGiftDetail;
            $giftModel->id = $gift->gift->id;
            $giftModel->name = $gift->gift->name;
            $giftModel->attr1_name = $gift->gift->attr1_name;
            if (is_array($gift->gift->attr1_data)) {
                foreach ($gift->gift->attr1_data as $item) {
                    $giftModel->attr1_options[$item] = $item;
                }
            }
            $giftModel->attr2_name = $gift->gift->attr2_name;
            if (is_array($gift->gift->attr2_data)) {
                foreach ($gift->gift->attr2_data as $item) {
                    $giftModel->attr2_options[$item] = $item;
                }
            }
            $giftModel->attr3_name = $gift->gift->attr3_name;
            if (is_array($gift->gift->attr3_data)) {
                foreach ($gift->gift->attr3_data as $item) {
                    $giftModel->attr3_options[$item] = $item;
                }
            }
            return $giftModel;
        }
        return parent::getGift();
    }

    public function getValidGift() {
        $total = $this->getTotalItems();
        $promotion = $this->getModel();
        $std = PromotionGift::find()
                ->andWhere(['promotion_id' => $promotion->id])
                ->andWhere(['<=', 'buy_rate', $total])
                ->orderBy(['buy_rate' => SORT_DESC])
                ->one();

        if (isset($std)) {
            $gifts = PromotionGift::find()
                    ->andWhere(['promotion_id' => $promotion->id])
                    ->andWhere(['<=', 'buy_rate', $total])
                    ->andWhere(['buy_rate' => $std->buy_rate])
                    ->orderBy(['buy_rate' => SORT_DESC])
                    ->all();

            return $gifts;
        }
    }

    public function rules() {
        $rules = [];
        $rules[] = [['buy_amount', 'coupon_code'], 'required'];
        $rules[] = [['buy_amount'], 'number', 'min' => 0];
        return $rules;
    }

    public function getPromotionName() {
        return $this->getModel()->name . ' : ' . ArrayHelper::getValue(Yii::$app->session->get('gift'), 'value');
    }

}
