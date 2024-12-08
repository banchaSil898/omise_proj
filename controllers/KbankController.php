<?php

namespace app\controllers;

use app\components\KBankHelper;
use app\models\Purchase;
use linslin\yii2\curl\Curl;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\base\Exception;

class KbankController extends Controller {

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionCustResponse() {
        try {
            if (Yii::$app->request->isPost) {
                $post = Yii::$app->request->post();
                $purchase = Purchase::findOne([
                            'purchase_no' => (int) ArrayHelper::getValue($post, 'RETURNINV'),
                ]);
                if (!isset($purchase)) {
                    throw new HttpException('Purchase order not found.');
                }
                if (!$purchase->isPaid) {
                    $curl = new Curl;
                    $response = $curl->setPostParams([
                                'USERNAME' => Yii::$app->params['kbank']['username'],
                                'TMERCHANTID' => Yii::$app->params['kbank']['merchantId'],
                                'TDATE' => date('dmY'),
                                'TINVOICE' => ArrayHelper::getValue($post, 'RETURNINV'),
                                'TAMOUNT' => ArrayHelper::getValue($post, 'AMOUNT'),
                                'TSTATUS' => 'A',
                            ])
                            ->post(Yii::$app->params['kbank']['queryUrl']);

                    if (substr($response, 0, 5) === 'ERROR') {
                        $purchase->doCancelPayment($response);
                        throw new Exception('Invalid kbank response.');
                    }

                    $data = KBankHelper::parseResponse($response);

                    $amount = (int) substr($data['TransAmount'], 0, -2);

                    if ($data['ResponseCode'] === '00' && $purchase->price_grand <= $amount) {
                        if ($purchase->doPaid('ชำระเงินผ่านบัตรเครดิต - ' . $data['ApprovalCode'])) {
                            return $this->redirect(['cart/success', 'order_no' => $purchase->purchase_no]);
                        }
                    } else {
                        $purchase->doCancelPayment();
                    }
                    throw new HttpException('Payment failed.');
                } else {
                    return $this->redirect(['cart/success', 'order_no' => $purchase->purchase_no]);
                }
            } else {
                throw new HttpException('Invalid request type.');
            }
        } catch (Exception $e) {
            $this->success($e->getMessage());
            return $this->redirect(['cart/error', 'order_no' => $purchase->purchase_no]);
        }
    }

    public function actionApiResponse() {
        if (Yii::$app->request->isGet) {
            throw new HttpException(400,'accept POST only.');
        }
        $resp = trim(Yii::$app->request->post('PMGWRESP'));
        //$resp = '00XXXXXXXXXXXX298045XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX00001002978429082018131258000000000100XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX764XXXXXXXXXXXX';

        if (strlen($resp) !== 222) {
            Yii::error('invalid PMGWRESP format : [' . $resp . ']', 'kbank-response');
        }

        Yii::info('KBank response : ' . $resp, 'kbank-response');
    }

    public function actionTest() {
        $curl = new Curl;
        $response = $curl->setPostParams([
                    'USERNAME' => '737ngandadm',
                    'TMERCHANTID' => Yii::$app->params['kbank']['merchantId'],
                    'TDATE' => '22052018',
                    'TINVOICE' => '000010000007',
                    'TAMOUNT' => '000000000100',
                    'TSTATUS' => 'A',
                ])
                ->post('https://rt05.kasikornbank.com/PGPayment/TransactionResponsedcc.aspx');
        var_dump(KBankHelper::parseResponse($response));
    }

}
