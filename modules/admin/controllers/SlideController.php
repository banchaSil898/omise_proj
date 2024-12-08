<?php

namespace app\modules\admin\controllers;

use app\models\ItemSelector;
use app\models\Product;
use app\models\ProductSelector;
use app\models\Promotion;
use app\models\Slide;
use app\modules\admin\components\UploadImageAction;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\Response;

class SlideController extends ContentController {

    public function actions() {
        return [
            'upload-file' => [
                'class' => UploadImageAction::className(),
                'model' => Slide::className(),
                'attribute' => 'image_file',
                'uploadUrl' => Yii::getAlias('@web/uploads/slides'),
                'uploadPath' => Yii::getAlias('@webroot/uploads/slides'),
                'imgOptions' => [
                    'class' => 'img-resp',
                ],
                'sizes' => [
                    'default' => [
                        'width' => 1920,
                        'height' => 842,
                        'extension' => 'jpg',
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new Slide;
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionLinkCreate() {
        $model = new Slide;
        $model->scenario = 'case-link';
        $model->slide_type = Slide::TYPE_LINK;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form-link', [
                    'model' => $model,
        ]);
    }

    public function actionHtmlCreate() {
        $model = new Slide;
        $model->scenario = 'case-html';
        $model->slide_type = Slide::TYPE_HTML;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form-html', [
                    'model' => $model,
        ]);
    }

    public function actionProductCreate() {
        $model = new Slide;
        $model->scenario = 'case-product';
        $model->slide_type = Slide::TYPE_PRODUCT;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form-product', [
                    'model' => $model,
        ]);
    }

    public function actionBundleCreate() {
        $model = new Slide;
        $model->scenario = 'case-bundle';
        $model->slide_type = Slide::TYPE_BUNDLE;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form-bundle', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Slide::findOne($id);
        switch ($model->slide_type) {
            case Slide::TYPE_BUNDLE:
                $model->slide_type = Slide::TYPE_BUNDLE;
                $model->scenario = 'case-bundle';
                break;
            case Slide::TYPE_PRODUCT:
                $model->slide_type = Slide::TYPE_PRODUCT;
                $model->scenario = 'case-product';
                break;
            case Slide::TYPE_HTML:
                $model->slide_type = Slide::TYPE_HTML;
                $model->scenario = 'case-html';
                break;
            default:
                $model->slide_type = Slide::TYPE_LINK;
                $model->scenario = 'case-link';
                break;
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        switch ($model->slide_type) {
            case Slide::TYPE_BUNDLE:
                return $this->render('form-bundle', [
                            'model' => $model,
                ]);
            case Slide::TYPE_PRODUCT:
                return $this->render('form-product', [
                            'model' => $model,
                ]);
            case Slide::TYPE_HTML:
                return $this->render('form-html', [
                            'model' => $model,
                ]);
            default:
                return $this->render('form-link', [
                            'model' => $model,
                ]);
        }
    }

    public function actionView($id) {
        $model = Slide::findOne($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Slide::findOne($id);
        if ($model->delete()) {
            
        }
    }

    public function actionGetProductList($q = null, $id = null, $page = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $ret = [];
        if (!$id) {
            $model = new Product;
            if ($q) {
                $model->search['text'] = $q;
            }
            $dataProvider = $model->search();
            if (isset($page)) {
                $dataProvider->getPagination()->setPage($page - 1);
            }
            foreach ($dataProvider->getModels() as $item) {
                $ret['results'][] = [
                    'id' => $item->id,
                    'text' => $item->name,
                ];
            }
            if ($dataProvider->getPagination()->getPageCount() > $page) {
                $ret['pagination']['more'] = true;
            }
        } else {
            $model = Product::findOne($id);
            $ret['results'] = [
                'id' => $model->id,
                'text' => $model->name,
            ];
        }
        return $ret;
    }

    public function actionBundleProduct($id) {
        $model = Slide::findOne($id);

        $item = new ItemSelector;
        $item->scenario = 'search';
        $item->load(Yii::$app->request->get());
        $itemProvider = $item->search();
        $itemProvider->query->andWhere(['IN', 'id', array_values(ArrayHelper::map($model->slideProducts, 'product_id', 'product_id'))]);
        $itemProvider->pagination->pageSize = 10;

        $product = new ProductSelector;
        $product->scenario = 'search';
        $product->load(Yii::$app->request->get());
        $productProvider = $product->search();
        $productProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->slideProducts, 'product_id', 'product_id'))]);
        $productProvider->pagination->pageSize = 10;
        return $this->render('bundle-product', [
                    'model' => $model,
                    'product' => $product,
                    'item' => $item,
                    'itemProvider' => $itemProvider,
                    'productProvider' => $productProvider,
        ]);
    }

    public function actionProductImport($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Slide::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            $product = new Product;
            $product->scenario = 'search';
            $product->search['text'] = ArrayHelper::getValue($data, 'text');
            $product->search['folder_id'] = ArrayHelper::getValue($data, 'folder_id');
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $dataProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->slideProducts, 'product_id', 'product_id'))]);
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->productAdd($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->productAdd($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

    public function actionProductRemove($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Slide::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            $product = new Product;
            $product->scenario = 'search';
            $product->search['text'] = ArrayHelper::getValue($data, 'text');
            $product->search['folder_id'] = ArrayHelper::getValue($data, 'folder_id');
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->productRemove($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->productRemove($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

    public function actionOrderUp($id) {
        $model = Slide::findOne($id);
        $model->doMoveUp();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionOrderDown($id) {
        $model = Slide::findOne($id);
        $model->doMoveDown();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

}
