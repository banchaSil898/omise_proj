<?php

namespace app\modules\admin\controllers;

use app\models\Author;
use app\modules\admin\components\UploadImageAction;
use codesk\components\Html;
use kartik\form\ActiveForm;
use Yii;
use yii\imagine\Image;
use yii\web\Response;
use yii\web\UploadedFile;

class AuthorController extends Controller {

    public function actions() {
        return [
            'upload-file' => [
                'class' => UploadImageAction::className(),
                'model' => Author::className(),
                'attribute' => 'photo_file',
                'uploadUrl' => Author::getUploadUrl(),
                'uploadPath' => Author::getUploadPath(),
                'sizes' => [
                    'default' => [
                        'width' => 200,
                        'height' => 200,
                        'extension' => 'jpg',
                    ],
                    'thumb' => [
                        'width' => 48,
                        'height' => 48,
                        'extension' => 'jpg',
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new Author;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Author;
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        if (!Yii::$app->request->isAjax) {
                            $this->success();
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Author::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        if (!Yii::$app->request->isAjax) {
                            $this->success();
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('form', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Author::findOne($id);
        if ($model->delete()) {
            
        }
    }

    public function actionToggleStatus($id, $attribute) {
        $model = Author::findOne($id);
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

}
