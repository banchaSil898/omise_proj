<?php

namespace app\controllers;

use app\models\AccountWishlist;
use app\models\MemberAddress;
use app\models\Product;
use app\models\Purchase;
use kartik\form\ActiveForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Response;

class MyController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function getUser() {
        return Yii::$app->user->identity;
    }

    public function actionIndex() {
        $model = $this->getUser();
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionWishlist() {
        $user = $this->getUser();

        $query = Product::find();
        $query->andWhere([
            'is_hide' => 0,
        ]);
        $query->joinWith('accountWishlists');
        $query->andWhere(['account_wishlist.account_id' => $user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('wishlist', [
                    'user' => $user,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionOrder() {
        $user = $this->getUser();
        $model = new Purchase;
        $model->scenario = 'search';
        $model->member_id = $user->id;
        $dataProvider = $model->search();
        $dataProvider->pagination->pageSize = 10;
        $dataProvider->sort = [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
        ];
        return $this->render('order', [
                    'user' => $user,
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAddress() {
        $user = $this->getUser();
        $model = new MemberAddress;
        $model->scenario = 'search';
        $model->member_id = $user->id;
        $dataProvider = $model->search();
        $dataProvider->sort = [
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
        ];
        return $this->render('address', [
                    'user' => $user,
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChangePassword() {
        $model = $this->getUser();
        $model->scenario = 'change-password';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->doChangePassword()) {
                $this->success();
                return $this->redirect(['change-password']);
            }
        }
        return $this->render('change-password', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate() {
        $model = $this->getUser();
        $model->scenario = 'updateProfile';
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
        return $this->renderAjax('update', [
                    'model' => $model,
        ]);
    }

    public function actionAddressCreate() {
        $model = $this->getUser();
        $address = new MemberAddress;
        $address->scenario = 'user-update';
        $address->firstname = $model->firstname;
        $address->lastname = $model->lastname;
        $address->member_id = $model->id;
        $address->country_id = 'TH';
        if ($address->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($address);
                default:
                    if ($address->save()) {
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('address-form', [
                    'model' => $model,
                    'address' => $address,
        ]);
    }

    public function actionAddressUpdate($address_id) {
        $model = $this->getUser();
        $address = MemberAddress::findOne([
                    'member_id' => $model->id,
                    'address_id' => $address_id,
        ]);
        $address->scenario = 'user-update';
        if ($address->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($address);
                default:
                    if ($address->save()) {
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('address-form', [
                    'model' => $model,
                    'address' => $address,
        ]);
    }

    public function actionAddressDelete($address_id) {
        $model = $this->getUser();
        $address = MemberAddress::findOne([
                    'member_id' => $model->id,
                    'address_id' => $address_id,
        ]);
        $address->delete();
    }

    public function actionToggleStatus($address_id, $attribute) {
        $model = $this->getUser();
        $address = MemberAddress::findOne([
                    'member_id' => $model->id,
                    'address_id' => $address_id,
        ]);
        $address->doPrimary();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['address']);
        }
    }

    public function actionDefaultBilling($id) {
        $model = $this->getUser();
        $address = MemberAddress::findOne([
                    'member_id' => $model->id,
                    'address_id' => $id,
        ]);
        $address->doBillingPrimary();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['address']);
        }
    }

    public function actionDefaultShipping($id) {
        $model = $this->getUser();
        $address = MemberAddress::findOne([
                    'member_id' => $model->id,
                    'address_id' => $id,
        ]);
        $address->doShippingPrimary();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['address']);
        }
    }

    public function actionWishlistDelete($id) {
        $model = AccountWishlist::findOne([
                    'product_id' => $id,
                    'account_id' => Yii::$app->user->id,
        ]);
        if (isset($model)) {
            $model->delete();
        }
    }

    public function actionReorder($id) {
        $user = $this->getUser();

        $model = Purchase::findOne($id);
        if (isset($model) && $model->member_id == $user->id) {
            if($model->doReorder()) {
                return $this->redirect(['cart/checkout']);
            }
        }
        throw new \yii\web\HttpException(400, 'Can not process your request.');
    }

}
