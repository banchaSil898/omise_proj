<?php

namespace app\controllers;

use Yii;

class ApiController extends Controller {

    public function actionCartSummary() {
        $cart = $this->getCart();
        $data = Yii::$app->request->get('Purchase');
        if (isset($data['delivery_method'])) {
            $cart->delivery_method = $data['delivery_method'];
        }
        $promotions = $cart->getPromotionSummary();

        return $this->renderAjax('cart-summary', [
                    'cart' => $cart,
                    'promotions' => $promotions,
        ]);
    }

}
