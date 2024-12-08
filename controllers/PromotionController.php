<?php

namespace app\controllers;

use app\models\Product;
use app\models\Promotion;
use app\models\PromotionItem;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Response;

class PromotionController extends StoreController {

    public $title = 'โปรโมชั่น';
    public $description = 'รายชื่อหนังสือโปรโมชั่น';

    public function getModel() {
        $model = new Product;
        $model->scenario = 'search';
        $model->is_promotion = true;
        $model->is_hide = 0;
        return $model;
    }

    public function actionProductAdd($promotion_id, $product_id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $promotion = Promotion::findOne($promotion_id);
        $product = Product::findOne($product_id);

        $found = PromotionItem::findOne([
                    'promotion_id' => $promotion->id,
                    'product_id' => $product->id,
        ]);

        if (!$found) {
            throw new HttpException(404);
        }
        $cart = $this->getCart();
        $manager = $promotion->getPromotionManager();
        if ($cart->getProductsCountByPromotion($promotion) >= ArrayHelper::getValue($manager, 'product_count', 0)) {
            return [
                'result' => false,
                'msg' => 'ไม่สามารถเพิ่มได้เกินจำนวน ' . $manager->product_count . ' เล่ม',
                'var' => [
                    $manager->product_count,
                    $cart->getProductsCountByPromotion($promotion),
                ],
            ];
        }
        $cart->addProductByPromotion($product, $promotion);
        return [
            'result' => true,
            'msg' => 'เพิ่มของแถมลงในรถเข็นเรียบร้อย',
        ];
    }

    public function actionProductRemove($promotion_id, $product_id) {
        $product = Product::findOne($product_id);
        $promotion = Promotion::findOne($promotion_id);

        $cart = $this->getCart();
        $cart->removeProductByPromotion($product, $promotion);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }

    public function actionProductClear($promotion_id, $product_id) {
        $product = Product::findOne($product_id);
        $promotion = Promotion::findOne($promotion_id);

        $cart = $this->getCart();
        $cart->clearProductByPromotion($product, $promotion);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return true;
    }

}
