<?php

namespace app\components\promotion;

use app\components\Cart;
use app\components\promotion\PromotionManagerInterface;
use app\models\Purchase;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class PromotionGeneralManager extends Model implements PromotionManagerInterface {

    public $showOnCart = true;
    public $type = 'afterCart';
    public $model;
    public $data;
    private $_promotionProducts;
    private $_cart;

    public function processPrice($price) {
        return $price;
    }

    public function getDiscount($price) {
        return ceil($price - $this->processPrice($price));
    }

    /**
     * 
     * @return \app\models\Promotion;
     */
    public function getModel() {
        return $this->model;
    }

    public function isValid($cart = null, $afterPrice = null) {
        return true;
    }

    public function attributeInputs() {
        return [];
    }

    public function loadCart(Cart $cart) {
        $this->_cart = $cart;
    }

    public function getCart() {
        return $this->_cart;
    }

    public function init() {
        parent::init();
        $data = ArrayHelper::getValue($this->getModel(), 'data');
        if ($data) {
            $this->attributes = Json::decode($data);
        }
    }

    public function save() {
        $data = [];
        foreach ($this->attributeInputs() as $attribute => $input) {
            $data[$attribute] = $this->{$attribute};
        }
        return $this->getModel()->updateAttributes([
                    'data' => Json::encode($data),
        ]);
    }

    public function getPromotionProducts() {
        if (!isset($this->_promotionProducts)) {
            $this->_promotionProducts = array_keys(ArrayHelper::map($this->getModel()->promotionProducts, 'product_id', 'product_id'));
        }
        return $this->_promotionProducts;
    }

    public function getIsPromotionProduct($id) {
        return in_array($id, $this->getPromotionProducts());
    }

    public function afterCheckout(Purchase $purchase, $price = 0) {
        return true;
    }

    public function getPromotionName() {
        return $this->getModel()->name;
    }

    public function getPromotionDetail() {
        return;
    }

    public function getGift() {
        return;
    }

}
