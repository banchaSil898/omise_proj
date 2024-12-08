<?php

namespace app\modules\admin\controllers;

use app\models\Gift;
use app\models\GiftImage;
use app\modules\admin\components\Html;
use Yii;
use yii\imagine\Image;
use yii\web\Response;
use yii\web\UploadedFile;

class GiftController extends Controller {

    public function actionIndex() {
        $model = new Gift;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Gift;
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
        $model = Gift::findOne($id);
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

    public function actionUpdateAttr($id) {
        $model = Gift::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-attr', 'id' => $model->id]);
            }
        }
        return $this->render('form-attr', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Gift::findOne($id);
        $model->delete();
    }

    public function actionUpdatePhoto($id) {
        $model = Gift::findOne($id);
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
        $model = Gift::findOne($id);

        $pf = new GiftImage;

        $ps = new GiftImage;
        $ps->scenario = 'search';
        $ps->gift_id = $model->id;
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

    public function actionUploadCover($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Gift::findOne($id);
        $file = UploadedFile::getInstance($model, 'image_url');
        if ($file) {
            Image::thumbnail($file->tempName, $model->cover_width, $model->cover_height)
                    ->save(Yii::getAlias('@webroot/uploads/gifts/') . $model->id . '.jpg');
            Image::thumbnail($file->tempName, $model->thumb_width, $model->thumb_height)
                    ->save(Yii::getAlias('@webroot/uploads/gifts/') . $model->id . '_thumb.jpg');

            $model->image_url = '@web/uploads/gifts/' . $model->id . '.jpg';
            $model->thumb_url = '@web/uploads/gifts/' . $model->id . '_thumb.jpg';
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
        $model = Gift::findOne($id);

        $file = UploadedFile::getInstance($model, 'image_url');
        if ($file) {
            $img = new GiftImage;
            $name = uniqid($model->id . '_');
            Image::getImagine()->open($file->tempName)->save(Yii::getAlias('@webroot/uploads/gifts/images/') . $name . '.jpg');
            Image::thumbnail($file->tempName, $img->thumb_width, $img->thumb_height)
                    ->save(Yii::getAlias('@webroot/uploads/gifts/images/') . $name . '_thumb.jpg');


            $img->gift_id = $model->id;
            $img->img_url = '@web/uploads/gifts/images/' . $name . '.jpg';
            $img->thumb_url = '@web/uploads/gifts/images/' . $name . '_thumb.jpg';
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

    public function actionDeleteImage($gift_id, $image_id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = GiftImage::findOne([
                    'gift_id' => $gift_id,
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

    public function actionPreviewImage($gift_id, $image_id) {
        $model = GiftImage::findOne([
                    'gift_id' => $gift_id,
                    'image_id' => $image_id,
        ]);
        return $this->renderAjax('preview-image', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateStockInline() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost && Yii::$app->request->post('hasEditable') == 1) {
            $data = Yii::$app->request->post();
            $product = Gift::findOne($data['editableKey']);

            $diff = ($data['stock'] - $product->stock);

            $product->stock = $data['stock'];
            $product->stock_est = $product->stock_est + $diff;
            if ($product->save()) {
                return [];
            }
        }
    }

}
