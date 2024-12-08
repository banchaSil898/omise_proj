<?php

namespace app\controllers;

use app\forms\Survey2019;
use Yii;
use yii\web\Response;

class SurveyController extends Controller {

    public function actionSave() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Survey2019;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return [
                'result' => true,
            ];
        } else {
            return [
                'result' => false,
            ];
        }
    }

}
