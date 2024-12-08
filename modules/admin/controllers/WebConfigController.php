<?php

namespace app\modules\admin\controllers;

use app\models\Configuration;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class WebConfigController extends Controller {

    public function actionIndex() {
        Configuration::getValuesByType('web');

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
            'query' => Configuration::find()->where(['config_group' => 'web']),
            'sort' => [
                'defaultOrder' => [
                    'order_no' => SORT_ASC,
                ],
            ]
        ]);


        return $this->render('index', [
                    'configDataProvider' => $configDataProvider,
        ]);
    }

}
