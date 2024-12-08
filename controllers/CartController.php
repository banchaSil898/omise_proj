<?php

namespace app\controllers;

use app\forms\Survey2019;
use app\models\Member;
use app\models\Product;
use app\models\Purchase;
use kartik\form\ActiveForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

class CartController extends Controller {

    public function init() {
        parent::init();
    }

    public function actionAdd($id, $addon_id = null) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = Product::findOne($id);

        if ($this->getCart()->addItem($product, $addon_id)) {
            return [
                'success' => true,
            ];
        } else {
            return [
                'success' => false,
            ];
        }
    }

    public function actionSelectAddon($id) {
        $model = Product::findOne($id);
        return $this->renderAjax('addon', [
                    'model' => $model,
        ]);
    }

    public function actionSetQty($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = Product::findOne($id);
        if ($this->getCart()->setItem($product, Yii::$app->request->post('qty', 0))) {
            return [
                'success' => true,
            ];
        } else {
            return [
                'success' => false,
            ];
        }
    }

    public function actionClear($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = Product::findOne($id);
        $this->getCart()->clearItem($product);
        return [
            'success' => true,
        ];
    }

    public function actionRemove($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $product = Product::findOne($id);
        $this->getCart()->removeItem($product);
        return [
            'success' => true,
        ];
    }

    public function actionApplyCoupon() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $code = Yii::$app->request->post('code');
        if (!$code) {
            return [
                'success' => false,
                'message' => 'กรุณาระบุรหัสส่วนลด',
            ];
        }
        $cart = $this->getCart();
        if ($cart->applyCoupon($code)) {
            $cart->updateCart();
            return [
                'success' => true,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'ไม่สามารถใช้รหัสนี้ได้',
            ];
        }
    }

    public function actionCouponRemove() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $cart = $this->getCart();
        $cart->removeCoupon();
        $cart->updateCart();
        return [
            'success' => true,
        ];
    }

    public function actionCheckout() {
        $cart = $this->getCart();
        if (!$cart->getItemsCount()) {
            return $this->redirect(Yii::$app->homeUrl);
        }

        $cartLog = [];


        /**
         * Survey 2019
         */
        /* @var $userIdentity Member */
        $survey2019 = new Survey2019;
        //Yii::$app->session->set('survey_2019', null);

        $user = Yii::$app->user;
        $userIdentity = $user->getIdentity();
        if (!$user->isGuest) {
            /*
              Yii::$app->session->set('survey_2019', [
              'flag' => $userIdentity->is_survey_2019,
              'allow' => $userIdentity->survey_2019_allow,
              'age' => $userIdentity->survey_2019_age,
              'graduate' => $userIdentity->survey_2019_graduate,
              'comment' => $userIdentity->survey_2019_comment,
              ]); */
        }

        $survey2019Options = Yii::$app->session->get('survey_2019');
        $targetProducts = ArrayHelper::getValue(Yii::$app, 'params.survey2019.productId', []);
        if ($cart->hasProducts($targetProducts)) {
            if (boolval(ArrayHelper::getValue($survey2019Options, 'flag')) === false) {
                if ($user->isGuest) {
                    $survey2019->enabled = true;
                } else {
                    if (boolval($userIdentity->is_survey_2019) === false) {
                        $userIdentity->updateAttributes([
                            'is_survey_2019' => 1,
                        ]);
                        $survey2019->enabled = true;
                    }
                }
            }
        }


        $model = new Purchase;
        $model->scenario = 'checkout';
        $model->status = Purchase::STATUS_NEW;
        $model->payment_method = null;
        $model->delivery_method = null;
        $model->invoice_country = 'TH';
        $model->delivery_country = 'TH';

        $model->survey_2019_age = ArrayHelper::getValue($survey2019Options, 'age');
        $model->survey_2019_comment = ArrayHelper::getValue($survey2019Options, 'comment');
        $model->survey_2019_graduate = ArrayHelper::getValue($survey2019Options, 'graduate');
        $model->survey_2019_allow = ArrayHelper::getValue($survey2019Options, 'allow');


        if (Yii::$app->user->isGuest) {
            $model->purchase_type = Purchase::TYPE_NOLOGIN;
            $model->delivery_same = true;
        } else {
            /* @var $user Member */
            $user = Yii::$app->user->identity;
            $model->purchase_type = Purchase::TYPE_LOGIN;
            $model->member_id = $user->id;
            $model->buyer_email = $user->username;
            $model->buyer_firstname = $user->firstname;
            $model->buyer_lastname = $user->lastname;
            $model->buyer_phone = $user->phone;

            $model->delivery_same = $user->default_addr_billing == $user->default_addr_shipping;

            $billingAddress = $user->defaultBillingAddress;
            if (isset($billingAddress)) {
                $model->invoice_firstname = $billingAddress->firstname;
                $model->invoice_lastname = $billingAddress->lastname;
                $model->invoice_company = $billingAddress->company_name;
                $model->invoice_tax = $billingAddress->tax_code;
                $model->invoice_address = $billingAddress->normalAddress;

                $model->invoice_tambon = $billingAddress->tambon;
                $model->invoice_amphur = $billingAddress->amphur;

                $model->invoice_province = $billingAddress->province;
                $model->invoice_postcode = $billingAddress->postcode;
                $model->invoice_phone = $billingAddress->phone;
                $model->invoice_country = $billingAddress->country_id;
            }

            $shippingAddress = $user->defaultShippingAddress;
            if (isset($shippingAddress)) {
                $model->delivery_firstname = $shippingAddress->firstname;
                $model->delivery_lastname = $shippingAddress->lastname;
                $model->delivery_company = $shippingAddress->company_name;
                $model->delivery_tax = $shippingAddress->tax_code;
                $model->delivery_address = $shippingAddress->normalAddress;

                $model->delivery_tambon = $shippingAddress->tambon;
                $model->delivery_amphur = $shippingAddress->amphur;

                $model->delivery_province = $shippingAddress->province;
                $model->delivery_postcode = $shippingAddress->postcode;
                $model->delivery_phone = $shippingAddress->phone;
                $model->delivery_country = $billingAddress->country_id;
            }
        }
        $model->loadCart($this->getCart());

        if ($model->load(Yii::$app->request->post())) {
            $cart->delivery_country = $model->delivery_country;
            switch (Yii::$app->request->get('mode')) {
                case 'update':
                    $model->validate();
                    $cart->delivery_method = $model->delivery_method;
                    $cart->updateCart();
                    break;
                case 'validate':
                    $errorFields = [];
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    foreach (Purchase::getStepValidation() as $step => $validate) {
                        $model->scenario = $validate;
                        $errors = ActiveForm::validate($model);
                        if (count($errors)) {
                            $errorFields = array_merge($errorFields, $errors);
                            if (!isset($errorFields['step'])) {
                                $errorFields['step'] = $step;
                            }
                            break;
                        }
                    }
                    return $errorFields;
                default:
                    if (Yii::$app->request->isAjax) {
                        $cart->delivery_method = $model->delivery_method;
                        $cart->updateCart();
                    } else {
                        if (Yii::$app->session->get('gift')) {
                            $model->gift_info = Yii::$app->session->get('gift');
                        }
                        $cartLog[] = 'ราคาสินค้า : ' . $cart->getTotal();
                        $cartLog[] = 'ราคาสินค้าหลังหักส่วนลด : ' . $cart->getTotalAfterDiscount();
                        $cartLog[] = 'การจัดส่ง : ' . $cart->delivery_method;
                        $cartLog[] = 'ค่าจัดสั่ง : ' . $cart->getDeliveryTotal();
                        $cartLog[] = 'รวมทั้งสิ้นราคา : ' . $cart->getGrandTotal();
                        $model->cart_log = implode("\n", $cartLog);
                        if ($model->doCheckout($this->getCart())) {
                            Yii::$app->session->set('gift', null);
                            Yii::$app->session->set('survey_2019', null);
                            switch ($model->payment_method) {
                                case Purchase::METHOD_CREDITCARD:
                                    $this->redirect(['payment', 'order_no' => $model->purchase_no]);
                                    break;
                                default:
                                    $this->redirect(['done', 'order_no' => $model->purchase_no]);
                                    break;
                            }
                        }
                    }
                    break;
            }
        }
        return $this->render('checkout', [
                    'survey2019' => $survey2019,
                    'model' => $model,
        ]);
    }

    public function actionGiftRemember() {
        $id = Yii::$app->request->post('id');
        $attribute = Yii::$app->request->post('attribute');
        $value = Yii::$app->request->post('value');
        Yii::$app->session->set('gift', [
            'id' => $id,
            'value' => $value,
            'amount' => 1,
        ]);
    }

    public function actionDone($order_no) {
        return $this->redirect(['order/view', 'order_no' => $order_no]);
    }

    public function actionPayment($order_no) {
        $model = Purchase::findOne([
                    'purchase_no' => $order_no,
        ]);
        return $this->render('payment', [
                    'model' => $model,
        ]);
    }

    public function actionResponse() {
        return $this->render('test');
    }

    public function actionTest() {
        return $this->render('test');
    }

    public function actionCallback() {
        
    }

    public function actionSuccess($order_no) {
        return $this->render('success', [
                    'order_no' => $order_no,
        ]);
    }

    public function actionError($order_no) {
        return $this->render('error', [
                    'order_no' => $order_no,
        ]);
    }

    public function actionSurvey2019($order_no) {
        $model = Purchase::findOne([
                    'purchase_no' => $order_no,
        ]);
        if (!isset($model)) {
            $this->success('ไม่พบใบสั่งซื้อ');
            return $this->redirect(['site/index']);
        }

        if (isset($model->member_id)) {
            if (Yii::$app->user->isGuest) {
                $this->success('กรุณาเข้าสู่ระบบ');
                return $this->redirect(['site/login', 'returnUrl' => Url::to(['order/view', 'order_no' => $order_no])]);
            }
            if (Yii::$app->user->id <> $model->member_id) {
                $this->success('ไม่มีสิทธิในการเข้าถึงข้อมูลใบสั่งซื้อ');
                return $this->redirect(['site/index']);
            }
        }

        return $this->render('survey-2019', [
                    'model' => $model,
        ]);
    }

}
