<?php

namespace app\controllers;

use app\models\Purchase;
use Yii;

class TransferController extends Controller {

    public function actionIndex($order_no = null) {
        $model = new Purchase;
        $model->scenario = 'check';
        $model->purchase_no = $order_no;
        if ($model->load(Yii::$app->request->post())) {
            $model->purchase_no = strtr($model->purchase_no, [
                '#' => '',
                ' ' => '',
            ]);
            if ($model->validate()) {
                return $this->redirect(['view', 'order_no' => $model->purchase_no]);
            }
        }
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionView($order_no) {
        return $this->redirect(['order/view', 'order_no' => $order_no]);
    }

}
