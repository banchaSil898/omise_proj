<?php

namespace app\modules\admin\controllers;

use app\models\Folder;
use Yii;
use yii\bootstrap\ActiveForm;
use yii\web\Response;

class ProductCategoryItemController extends Controller {

    public function actionCreate($id) {

        $category = Folder::findOne($id);

        $model = new Folder;
        $model->folder_id = $category->id;
        $model->level = 1;
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['product/index']);
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
                            return $this->redirect(['product/index']);
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
            return $this->redirect(['product/index']);
        }
    }

}
