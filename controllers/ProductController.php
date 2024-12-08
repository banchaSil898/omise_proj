<?php

namespace app\controllers;

use app\controllers\Controller;
use app\models\Product;
use app\models\ProductImage;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;

class ProductController extends Controller {

    public function actionView($id) {
        $model = Product::findOne($id);

        if (!$model->isUserVisible) {
            throw new HttpException(404, 'ไม่พบรายการสินค้า');
        }
        if (boolval($model->is_deleted)) {
            throw new HttpException(404, 'ไม่พบรายการสินค้า');
        }
        if (boolval($model->is_hide)) {
            throw new HttpException(404, 'ไม่พบรายการสินค้า');
        }
        $relate = Product::find();
        $relate->joinWith('productRelates');
        $relate->andWhere(['product_relate.product_id' => $model->id]);
        $relateProvider = new ActiveDataProvider([
            'query' => $relate,
        ]);

        switch ($model->product_type) {
            case Product::TYPE_FOLDER:
            case Product::TYPE_BUNDLE:
                $itemProvider = new ActiveDataProvider([
                    'query' => $model->getProductBundles(),
                    'pagination' => false,
                ]);
                return $this->render('view', [
                            'model' => $model,
                            'itemProvider' => $itemProvider,
                            'relateProvider' => $relateProvider,
                ]);
            default:
                return $this->render('view', [
                            'model' => $model,
                            'relateProvider' => $relateProvider,
                ]);
        }
    }

    public function actionPreview($id) {
        $model = Product::findOne($id);
        return $this->renderAjax('preview', [
                    'model' => $model,
        ]);
    }

    public function actionImage($product_id, $image_id) {
        $model = ProductImage::findOne([
                    'product_id' => $product_id,
                    'image_id' => $image_id,
        ]);
        return $this->renderAjax('image', [
                    'model' => $model,
        ]);
    }

    public function actionLove($id) {
        $model = Product::findOne($id);
        return $model->toggleLove();
    }

}
