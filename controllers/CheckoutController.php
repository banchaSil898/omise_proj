<?php

namespace app\controllers;

use app\models\Member;
use app\models\Purchase;
use kartik\widgets\ActiveForm;
use Yii;
use yii\web\Response;

class CheckoutController extends Controller {

    public function init() {
        parent::init();
    }

    public function actionStart() {
        $cart = $this->getCart();
        if (!$cart->getItemsCount()) {
            return $this->redirect(Yii::$app->homeUrl);
        }

        $model = new Purchase;
        $model->scenario = 'checkout';
        $model->status = Purchase::STATUS_NEW;
        $model->payment_method = null;
        $model->delivery_method = null;
        $model->invoice_country = 'TH';
        $model->delivery_country = 'TH';

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
                $model->invoice_address = $billingAddress->shortAddress;
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
                $model->delivery_address = $shippingAddress->shortAddress;
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
                        if ($model->doCheckout($this->getCart())) {
                            Yii::$app->session->set('gift', null);
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
        return $this->render('start', [
                    'model' => $model,
        ]);
    }

}
