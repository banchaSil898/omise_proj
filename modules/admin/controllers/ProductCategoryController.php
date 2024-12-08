<?php

namespace app\modules\admin\controllers;

use app\models\Folder;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

class ProductCategoryController extends Controller {

    public function actionIndex() {
        $categories = Folder::find()->andWhere(['level' => 0])->orderBy(['position' => SORT_ASC])->all();
        return $this->render('index', [
                    'categories' => $categories,
        ]);
    }

    public function actionCreate() {
        $model = new Folder;
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

    public function actionUpdate($id) {
        $model = Folder::findOne($id);
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
        $model = Folder::findOne($id);
        if (isset($model)) {
            $model->delete();
        }
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionMoveUp($id) {
        $model = Folder::findOne($id);
        $model->doMoveUp();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionMoveDown($id) {
        $model = Folder::findOne($id);
        $model->doMoveDown();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

}
