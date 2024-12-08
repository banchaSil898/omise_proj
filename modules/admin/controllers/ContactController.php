<?php

namespace app\modules\admin\controllers;

use app\models\Contact;
use app\modules\admin\controllers\Controller;
use kartik\form\ActiveForm;
use Yii;
use yii\web\Response;

class ContactController extends Controller {

    public function actionIndex() {
        $model = new Contact;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        $dataProvider->sort = [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
        ];
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Contact;
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
        $model = Contact::findOne($id);
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
        $model = Contact::findOne($id);
        if ($model->delete()) {
            
        }
    }

    public function actionToggleStatus($id, $attribute) {
        $model = Contact::findOne($id);
        if ($model->{$attribute}) {
            $model->{$attribute} = 0;
        } else {
            $model->{$attribute} = 1;
        }
        $model->save();
        if (!Yii::$app->request->isAjax) {
            $this->success();
            return $this->redirect(['index']);
        }
    }

}
