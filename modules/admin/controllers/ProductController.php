<?php

namespace app\modules\admin\controllers;

use app\models\Product;
use app\models\ProductBundleItem;
use app\models\ProductImage;
use app\models\ProductRelate;
use app\models\ProductStock;
use app\modules\admin\components\UploadFileAction;
use codesk\components\Html;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\imagine\Image;
use yii\web\Response;
use yii\web\UploadedFile;

class ProductController extends Controller {

    public function actions() {
        return [
            'upload-ebook' => [
                'class' => UploadFileAction::className(),
                'model' => Product::className(),
                'attribute' => 'ebook_file',
                'uploadUrl' => Product::getEbookUploadUrl(),
                'uploadPath' => Product::getEbookUploadPath(),
            ],
        ];
    }

    public function actionCopy($id) {
        $src = Product::findOne($id);
        $dest = $src->copy();
        if (isset($dest)) {
            $this->success('สำเนาเรียบร้อย...' . Html::a('[แสดงข้อมูล]', ['update', 'id' => $dest->id]));
        } else {
            $this->success('ไม่สามารถดำเนินการได้');
        }
        return $this->redirect(['index']);
    }

    public function actionIndex() {
        $model = new Product;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        $dataProvider->sort = [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
        ];
        Url::remember('', 'product');
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBulkDelete() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $items = Yii::$app->request->post('items', []);
        foreach ($items as $id) {
            $product = Product::findOne($id);
            if (isset($product)) {
                if ($product->delete()) {
                    
                }
            }
        }
        return [
            'result' => '1',
        ];
    }

    public function actionCreate() {
        $model = new Product;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Product::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdatePhoto($id) {
        $model = Product::findOne($id);
        $model->scenario = 'update-photo';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-photo', 'id' => $model->id]);
            }
        }
        return $this->render('form-photo', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateImage($id) {
        $model = Product::findOne($id);
        $model->scenario = 'update-image';

        $pf = new ProductImage;

        $ps = new ProductImage;
        $ps->scenario = 'search';
        $ps->product_id = $model->id;
        $dataProvider = $ps->search();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-image', 'id' => $model->id]);
            }
        }
        return $this->render('form-image', [
                    'model' => $model,
                    'ps' => $ps,
                    'pf' => $pf,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdatePrice($id) {
        $model = Product::findOne($id);
        $model->scenario = 'update-price';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-price', 'id' => $model->id]);
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $model->getProductAddons(),
        ]);

        return $this->render('form-price', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateCategory($id) {
        $model = Product::findOne($id);
        if (Yii::$app->request->isPost) {
            $items = Yii::$app->request->post('items', []);
            $model->saveCategoryItems($items);
            return $this->redirect(['update-category', 'id' => $model->id]);
        }
        $categoryItems = $model->getProductFolders()->indexBy('folder_id')->asArray()->all();
        return $this->render('form-category', [
                    'model' => $model,
                    'categoryItems' => $categoryItems,
        ]);
    }

    public function actionUpdateEbook($id) {
        $model = Product::findOne($id);
        $model->scenario = 'update-ebook';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-ebook', 'id' => $model->id]);
            }
        }
        return $this->render('form-ebook', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateMeta($id) {
        $model = Product::findOne($id);
        $model->scenario = 'update-meta';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-meta', 'id' => $model->id]);
            }
        }
        return $this->render('form-meta', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateRelate($id) {
        $model = Product::findOne($id);

        $relate = new ProductRelate;
        $relate->product_id = $model->id;

        $query = ProductRelate::find();
        $query->andWhere(['product_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('form-relate', [
                    'model' => $model,
                    'relate' => $relate,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateBundle($id) {
        $model = Product::findOne($id);

        $relate = new ProductBundleItem;
        $relate->bundle_id = $model->id;

        $query = ProductBundleItem::find();
        $query->andWhere(['bundle_id' => $model->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('form-bundle', [
                    'model' => $model,
                    'relate' => $relate,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateStock($id) {
        $model = Product::findOne($id);

        $stock = new ProductStock;
        $stock->product_id = $model->id;
        if ($stock->load(Yii::$app->request->post())) {
            if ($stock->save()) {
                $this->success();
                return $this->redirect(['update-stock', 'id' => $stock->product_id]);
            }
        }
        $sModel = new ProductStock;
        $sModel->scenario = 'search';
        $sModel->product_id = $model->id;
        $dataProvider = $sModel->search();

        return $this->render('form-stock', [
                    'model' => $model,
                    'stock' => $stock,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateStockInline() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost && Yii::$app->request->post('hasEditable') == 1) {
            $data = Yii::$app->request->post();
            $product = Product::findOne($data['editableKey']);
            $stock = new ProductStock;
            $stock->product_id = $product->id;
            $stock->amount = $data['stock'] - $product->stock;
            $stock->description = 'แก้ไขจำนวนสินค้าจากตาราง';
            if ($stock->save()) {
                return [];
            }
        }
    }

    public function actionUpdatePreview($id) {
        $model = Product::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-preview', 'id' => $model->id]);
            }
        }
        return $this->render('form-preview', [
                    'model' => $model,
        ]);
    }

    public function actionView($id) {
        $model = Product::findOne($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Product::findOne($id);
        $model->delete();
    }

    public function actionUploadCover($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Product::findOne($id);
        $file = UploadedFile::getInstance($model, 'cover_url');
        if ($file) {
            $name = uniqid('cover_');
            Image::thumbnail($file->tempName, $model->cover_width, $model->cover_height)
                    ->save(Yii::getAlias('@webroot/uploads/products/') . $name . '.jpg');
            Image::thumbnail($file->tempName, $model->thumb_width, $model->thumb_height)
                    ->save(Yii::getAlias('@webroot/uploads/products/') . $name . '_thumb.jpg');

            $model->cover_url = '@web/uploads/products/' . $name . '.jpg';
            $model->thumb_url = '@web/uploads/products/' . $name . '_thumb.jpg';
            $model->save();
            return [
                'initialPreview' => [
                    Html::img($model->thumbUrl . '?t=' . time(), ['width' => $model->thumb_width, 'height' => $model->thumb_height]),
                ],
            ];
        } else {
            return [
                'error' => 'Error on upload file.',
            ];
        }
    }

    public function actionUploadImage($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Product::findOne($id);

        $file = UploadedFile::getInstance($model, 'image_url');
        if ($file) {
            $img = new ProductImage;
            $name = uniqid('img_');
            Image::getImagine()->open($file->tempName)->save(Yii::getAlias('@webroot/uploads/products/images/') . $name . '.jpg');
            Image::thumbnail($file->tempName, $img->thumb_width, $img->thumb_height)
                    ->save(Yii::getAlias('@webroot/uploads/products/images/') . $name . '_thumb.jpg');


            $img->product_id = $model->id;
            $img->img_url = '@web/uploads/products/images/' . $name . '.jpg';
            $img->thumb_url = '@web/uploads/products/images/' . $name . '_thumb.jpg';
            $img->save();
            return [
                'initialPreview' => [
                    Html::img($img->thumbUrl . '?t=' . time()),
                ],
            ];
        } else {
            return [
                'error' => 'Error on upload file.',
            ];
        }
    }

    public function actionToggleStatus($id, $attribute) {
        $model = Product::findOne($id);
        if ($model->{$attribute}) {
            $model->{$attribute} = 0;
        } else {
            $model->{$attribute} = 1;
        }
        $model->save();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionGetRelateList($q = null, $id = null, $page = null) {
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

    public function actionGetBundleList($q = null, $id = null, $page = null) {
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

    public function actionSetFlag() {
        $items = Yii::$app->request->post('items');
        $flag = Yii::$app->request->post('flag');

        $products = Product::findAll([
                    'id' => $items
        ]);
        foreach ($products as $product) {
            /* @var $product Product */
            switch ($flag) {
                case 'new':
                    $product->is_new = $product->is_new ? 0 : 1;
                    break;
                case 'recommended':
                    $product->is_recommended = $product->is_recommended ? 0 : 1;
                    break;
                case 'bestseller':
                    $product->is_bestseller = $product->is_bestseller ? 0 : 1;
                    break;
                case 'show':
                    $product->is_hide = $product->is_hide ? 0 : 1;
                    break;
            }
            $product->save();
        }
    }

    public function actionAddRelate($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $relate_id = Yii::$app->request->post('relate_id');
        $model = Product::findOne($id);
        if ($model->relateAdd($relate_id)) {
            return [
                'data' => [
                ]
            ];
        } else {
            return [
                'errors' => [
                    [
                    ]
                ],
            ];
        }
    }

    public function actionAddBundleItem($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $relate_id = Yii::$app->request->post('product_id');
        $model = Product::findOne($id);
        if ($model->bundleAdd($relate_id)) {
            return [
                'data' => [
                ]
            ];
        } else {
            return [
                'errors' => [
                    [
                    ]
                ],
            ];
        }
    }

    public function actionDeleteRelate($product_id, $relate_id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = ProductRelate::findOne([
                    'product_id' => $product_id,
                    'relate_id' => $relate_id,
        ]);
        if (isset($model)) {
            if ($model->delete()) {
                return [
                    'data' => [],
                ];
            }
        }
    }

    public function actionDeleteBundleItem($bundle_id, $product_id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = ProductBundleItem::findOne([
                    'bundle_id' => $bundle_id,
                    'product_id' => $product_id,
        ]);
        if (isset($model)) {
            if ($model->delete()) {
                return [
                    'data' => [],
                ];
            }
        }
    }

    public function actionDeleteImage($product_id, $image_id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = ProductImage::findOne([
                    'product_id' => $product_id,
                    'image_id' => $image_id,
        ]);
        if (isset($model)) {
            if ($model->delete()) {
                return [
                    'data' => [],
                ];
            }
        }
    }

    public function actionPreviewImage($product_id, $image_id) {
        $model = ProductImage::findOne([
                    'product_id' => $product_id,
                    'image_id' => $image_id,
        ]);
        return $this->renderAjax('preview-image', [
                    'model' => $model,
        ]);
    }

}
