<?php

namespace app\modules\admin\controllers;

use app\models\WordConvert;
use kartik\form\ActiveForm;
use Yii;
use yii\web\Response;

class WordConvertController extends Controller {

    public function actionIndex() {
        $model = new WordConvert;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new WordConvert;
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
        $model = WordConvert::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        if (!Yii::$app->request->isAjax) {
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
        $model = WordConvert::findOne($id);
        if ($model->delete()) {
            
        }
    }

}
