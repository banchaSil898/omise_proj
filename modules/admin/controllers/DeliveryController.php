<?php

namespace app\modules\admin\controllers;

use app\models\Configuration;
use app\models\DeliveryRate;
use app\models\Purchase;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\widgets\ActiveForm;

class DeliveryController extends Controller {

    public function actionIndex() {
        Configuration::getValuesByType('delivery');

        if (Yii::$app->request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $keys = Yii::$app->request->post('Configuration');
            $key = array_pop($keys);
            $val = array_pop($key);
            Configuration::setValue(Yii::$app->request->post('editableKey'), $val);
            return [
                'status' => 'success',
            ];
        }

        $configDataProvider = new ActiveDataProvider([
            'query' => Configuration::find()->where(['config_group' => 'delivery']),
            'sort' => [
                'defaultOrder' => [
                    'order_no' => SORT_ASC,
                ],
            ]
        ]);

        $model = new DeliveryRate;
        $model->scenario = 'search';
        if (Yii::$app->request->get('_pjax') === '#pjax-page') {
            $model->load(Yii::$app->request->get());
        }
        $model->delivery_id = Purchase::DELIVERY_EMS;
        $dataProvider = $model->search();
        $dataProvider->pagination = false;

        $customModel = new DeliveryRate;
        $customModel->scenario = 'search';
        if (Yii::$app->request->get('_pjax') === '#pjax-page') {
            $model->load(Yii::$app->request->get());
        }
        $customModel->delivery_id = Purchase::DELIVERY_CUSTOM;
        $customProvider = $customModel->search();
        $customProvider->pagination = false;

        $airModel = new DeliveryRate;
        $airModel->scenario = 'search';
        $airModel->country_id = '*';
        if (Yii::$app->request->get('_pjax') === '#pjax-airmail') {
            $airModel->load(Yii::$app->request->get());
        }
        $airModel->delivery_id = Purchase::DELIVERY_AIRMAIL;
        $airDataProvider = $airModel->search();
        $airDataProvider->pagination = false;

        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'configDataProvider' => $configDataProvider,
                    'airDataProvider' => $airDataProvider,
                    'customProvider' => $customProvider,
                    'airModel' => $airModel,
        ]);
    }

    public function actionCreate($id = null) {
        $id = isset($id) ? $id : DeliveryRate::TYPE_EMS;
        $model = new DeliveryRate;
        $model->country_id = '*';
        $model->delivery_id = intval($id);
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['index', 'tab' => 'xtab' . $model->delivery_id]);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('ajax-rate', [
                    'model' => $model,
        ]);
    }

    public function actionCreateAirmail($country_id) {
        $model = new DeliveryRate;
        $model->delivery_id = Purchase::DELIVERY_AIRMAIL;
        $model->country_id = $country_id;
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
        return $this->renderAjax('ajax-rate-airmail', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($delivery_id, $weight, $country_id = '*') {
        $model = DeliveryRate::findOne([
                    'delivery_id' => $delivery_id,
                    'weight' => $weight,
                    'country_id' => $country_id,
        ]);
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
        if ($country_id <> '*') {
            return $this->renderAjax('ajax-rate-airmail', [
                        'model' => $model,
            ]);
        }
        return $this->renderAjax('ajax-rate', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($delivery_id, $weight, $country_id) {
        $model = DeliveryRate::findOne([
                    'delivery_id' => $delivery_id,
                    'weight' => $weight,
                    'country_id' => $country_id,
        ]);
        if ($model->delete()) {
            
        }
    }

}
