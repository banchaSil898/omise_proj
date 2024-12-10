<?php

namespace app\controllers;

use Yii;
use app\models\Purchase;
use app\models\PurchaseStatus;
use app\models\OmisePayments;
require_once Yii::getAlias('@omise/Omise.php');  // เรียกใช้ Omise SDK ที่กำหนดใน aliases

class ApiController extends Controller {
    // ปิดการใช้งาน CSRF validation สำหรับ Action นี้
    public $enableCsrfValidation = false;

    public function actionCartSummary() {
        $cart = $this->getCart();
        $data = Yii::$app->request->get('Purchase');
        if (isset($data['delivery_method'])) {
            $cart->delivery_method = $data['delivery_method'];
        }
        $promotions = $cart->getPromotionSummary();

        return $this->renderAjax('cart-summary', [
                    'cart' => $cart,
                    'promotions' => $promotions,
        ]);
    }

    public function actionComplete(){

        $request = Yii::$app->request;
        if ($request->isPost) {
            $dataFromOmise = json_decode(file_get_contents('php://input'));

            if (isset($dataFromOmise->key) && ($dataFromOmise->data->status === 'successful')) {
                try {
                    $chargeData = OmisePayments::find()
                        ->with('purchase')
                        ->where(['charge_id' => $dataFromOmise->data->id])
                        ->one(); // เรียก one() เพื่อให้ได้ออบเจ็กต์ของ OmisePayments
                } catch (\Exception $e) {
                    $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                    Yii::error([
                        'message' => 'An error occurred!',
                        'location' => [
                            'file' => $trace[0]['file'] ?? null,
                            'line' => $trace[0]['line'] ?? null,
                            'charge id' => $dataFromOmise->data->id,
                            'purchase_no' => $chargeData->purchase->purchase_no,
                            'error' => $e->getMessage()
                        ],
                    ]);
                }

                if(($dataFromOmise->data->amount/100) - $chargeData->purchase->price_grand == 0){

                    $updated_date = new \DateTime();
                    $updated_date->setTimezone(new \DateTimeZone('Asia/Bangkok'));

                    $payment_date = new \DateTime($dataFromOmise->data->paid_at);
                    $payment_date->setTimezone(new \DateTimeZone('Asia/Bangkok'));

                    $commonDescription = "ชำระผ่าน omise รหัส " . $dataFromOmise->data->id;
                    $chargeData->purchase->status = Purchase::STATUS_PAID;
                    $chargeData->purchase->status_comment = $commonDescription;
                    $chargeData->purchase->payment_info = $commonDescription;
                    $chargeData->purchase->is_paid = 1;
                    $chargeData->purchase->payment_date = $payment_date->format('Y-m-d H:i:s');
                    $chargeData->purchase->updated_at = $updated_date->format('Y-m-d H:i:s');

                    $purchaseStatus = new PurchaseStatus;
                    $purchaseStatus->purchase_id = $chargeData->purchase->id;
                    $purchaseStatus->created_at = $payment_date;
                    $purchaseStatus->updated_at = $updated_date;
                    $purchaseStatus->description = $commonDescription;
                    $purchaseStatus->status = Purchase::STATUS_PAID;
                    $purchaseStatus->is_sendmail = 1;
                    try{
                        $chargeData->purchase->save();
                        $purchaseStatus->save();
                    }catch(\Exception $exc){
                        $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
                        Yii::error([
                            'message' => 'An error occurred!',
                            'location' => [
                                'file' => $trace[0]['file'] ?? null,
                                'line' => $trace[0]['line'] ?? null,
                                'charge id' => $dataFromOmise->data->id,
                                'purchase_no' => $chargeData->purchase->purchase_no,
                                'error' => $exc->getMessage(),
                                'query' => [$purchaseStatus->errors, $chargeData->purchase->errors                                                                                                                                                          ]
                            ],
                        ]);
                    }
                }
            }
        }
        // Access Token ของ LINE Notify
        $lineToken = 'qtSUj8j53ar1SZ0MC2KczBfEYE41tejVCGLxvpnErK0';

        // ข้อความที่ต้องการส่ง
        $message = "ชำระผ่าน omise รหัส " . $dataFromOmise->data->id . " เรียบร้อยแล้ว";

        $url = 'https://notify-api.line.me/api/notify';
        $headers = [
            'Authorization: Bearer ' . $lineToken,
        ];

        $data = [
            'message' => $message,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);
    }
}
