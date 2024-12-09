<?php

namespace app\controllers;

use app\models\Purchase;
use Yii;
use yii\helpers\Url;
require_once Yii::getAlias('@omise/Omise.php');  // เรียกใช้ Omise SDK ที่กำหนดใน aliases

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
        $model = Purchase::find()
        ->where(['purchase_no' => $order_no])
        ->with('omisePayments')
        ->one();

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

        if($model->omisePayments->charge_id){
            $charge = json_decode($this->getOmiseQrPromptpay($model->omisePayments->charge_id));
            if($charge->status == 'expired'){
                define('OMISE_PUBLIC_KEY', Yii::$app->params['omisePublicKey']);
                define('OMISE_SECRET_KEY', Yii::$app->params['omiseSecretKey']);
                try {
                    //code...
                    $source = \OmiseSource::create([
                        'type' => 'promptpay',
                        'amount' => $charge->amount,
                        'currency' => 'thb', // สกุลเงิน
                    ]);

                    // สร้าง Charge โดยใช้ Source ที่สร้างขึ้น
                    $newCharge = \OmiseCharge::create([
                        'amount' => $charge->amount,
                        'currency' => 'thb', // สกุลเงิน
                        'source' => $source['id'],  // ใช้ Source ที่สร้างจาก PromptPay
                        'return_uri' => Url::to(['/cart/complete'] ,true), // URL ที่จะ redirect เมื่อชำระเงินสำเร็จ,
                        'metadata' => null
                    ]);

                    $model->omisePayments->charge_id = $newCharge['id'];
                    $created_date = new \DateTime();
                    $created_date->setTimezone(new \DateTimeZone('Asia/Bangkok'));
                    $model->omisePayments->created_at = $created_date->format('Y-m-d H:i:s');
                    $model->omisePayments->save();
                    
                } catch (\Exception $e) {
                    Yii::error("Failed to create omise charge relation: " . $e->getMessage(), __METHOD__);
                }
            }
        }

        return $this->render('view', ['model' => $model, 'omise_qr_uri' => $newCharge['source']['scannable_code']['image']['download_uri'] ?? $charge->source->scannable_code->image->download_uri ?? null]);
    }

    private function getOmiseQrPromptpay($charge_key){
        // ตั้งค่า URL ที่ต้องการเรียกใช้
        $url = 'https://api.omise.co/charges/'. $charge_key;

        // ตั้งค่า Secret Key (ห้ามแสดงในโค้ด client-side)
        $secretKey = Yii::$app->params['omiseSecretKey'];
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url, // URL สำหรับ API
            CURLOPT_RETURNTRANSFER => true, // ให้ return response กลับมา
            CURLOPT_CUSTOMREQUEST => 'GET', // ระบุ HTTP Method (GET ในที่นี้)
            CURLOPT_USERPWD => $secretKey . ':', // ใส่ Secret Key พร้อม `:` (เหมือน -u ใน curl)
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json', // Content-Type (ถ้าจำเป็น)
            ),
        ));

        // ส่ง request และรับ response
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
