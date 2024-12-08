<?php

namespace app\controllers;

use app\models\Purchase;
use Yii;
use yii\helpers\Url;

class OrderController extends Controller {

    public function actionIndex() {
        $model = new Purchase;
        $model->scenario = 'check';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                return $this->redirect(['view', 'order_no' => $model->purchase_no]);
            }
        }
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionView($order_no) {
        $model = Purchase::findOne([
                    'purchase_no' => $order_no,
        ]);
        if (!isset($model)) {
            $this->success('ไม่พบใบสั่งซื้อ');
            return $this->redirect(['index']);
        }

        if (isset($model->member_id)) {
            if (Yii::$app->user->isGuest) {
                $this->success('กรุณาเข้าสู่ระบบ');
                return $this->redirect(['site/login', 'returnUrl' => Url::to(['order/view', 'order_no' => $order_no])]);
            }
            if (Yii::$app->user->id <> $model->member_id) {
                $this->success('ไม่มีสิทธิในการเข้าถึงข้อมูลใบสั่งซื้อ');
                return $this->redirect(['index']);
            }
        }
        $model->scenario = 'transfer';
        $model->transfer_date = date('Y-m-d');
        $model->transfer_time = date('H:i');
        if ($model->load(Yii::$app->request->post())) {
            if ($model->doTransfer()) {
                $this->success('<br/><div class="text-center"><strong>แจ้งโอนเรียบร้อย</strong></div><br/><p><small>หมายเหตุ : เจ้าหน้าที่จะทำการตรวจสอบยอดการชำระเงินของท่านระหว่างเวลา 9.00-17.00 น.ของทุกวันจันทร์-วันศุกร์ หากท่านแจ้งการชำระเงินมาหลังจากเวลาดังกล่าว เจ้าหน้าที่จะทำการตรวจสอบและแจ้งกลับทางเมล์ในวันถัดไป</small></p>');
                return $this->redirect(['view', 'order_no' => $order_no]);
            }
        }
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

}
