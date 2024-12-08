<?php

namespace app\modules\admin\controllers;

use app\models\Product;
use app\models\ProductAddon;
use Yii;

class ProductAddonController extends Controller {

    public function actionCreate($id) {
        $product = Product::findOne($id);

        $model = new ProductAddon;
        $model->product_id = $product->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['product/update-price', 'id' => $product->id]);
        }

        return $this->renderAjax('form', [
                    'product' => $product,
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = ProductAddon::findOne($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['product/update-price', 'id' => $model->product_id]);
        }

        return $this->renderAjax('form', [
                    'product' => $model->product,
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = ProductAddon::findOne($id);
        $model->delete();
    }

}
