<?php

namespace app\modules\admin\controllers;

use app\components\MicMailer;
use app\models\Configuration;
use app\models\MailBody;
use kartik\form\ActiveForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class MailController extends Controller {

    public function init() {
        parent::init();
    }

    public function actionIndex() {
        Configuration::getValuesByType('mail');

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

        $mail = new MailBody;
        $mail->scenario = 'search';
        $dataProvider = $mail->search();

        $configDataProvider = new ActiveDataProvider([
            'query' => Configuration::find()->where(['config_group' => 'mail']),
            'sort' => [
                'defaultOrder' => [
                    'order_no' => SORT_ASC,
                ],
            ]
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'configDataProvider' => $configDataProvider,
        ]);
    }

    public function actionLayoutUpdate($mode = null) {
        $header = Configuration::findOne([
                    'name' => 'mail_header' . ($mode ? '_' . $mode : ''),
        ]);
        $footer = Configuration::findOne([
                    'name' => 'mail_footer' . ($mode ? '_' . $mode : ''),
        ]);
        if (Yii::$app->request->isPost) {
            $header->data = Yii::$app->request->post('header');
            $footer->data = Yii::$app->request->post('footer');
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validateMultiple([$header, $footer]);
                default:
                    if ($header->save() && $footer->save()) {
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('layout-update', [
                    'header' => $header,
                    'footer' => $footer,
        ]);
    }

    public function actionLayoutPreview($mode = null) {
        $header = Configuration::findOne([
                    'name' => 'mail_header' . ($mode ? '_' . $mode : ''),
        ]);
        $footer = Configuration::findOne([
                    'name' => 'mail_footer' . ($mode ? '_' . $mode : ''),
        ]);
        return $this->renderAjax('layout-preview', [
                    'header' => $header,
                    'footer' => $footer,
        ]);
    }

    public function actionUpdate($id) {
        $model = MailBody::findOne($id);
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
        return $this->renderAjax('form', [
                    'model' => $model,
        ]);
    }

    public function actionView($id) {
        $header = Configuration::findOne([
                    'name' => 'mail_header',
        ]);
        $footer = Configuration::findOne([
                    'name' => 'mail_footer',
        ]);
        $model = MailBody::findOne($id);
        return $this->renderAjax('view', [
                    'header' => $header,
                    'model' => $model,
                    'footer' => $footer
        ]);
    }

    public function actionTest() {
        if (Yii::$app->request->isPost) {
            $mail = new MicMailer;
            $mail->subject = 'Test Mail';
            $mail->body = 'Test mail sent successfully.';
            $mail->send([
                Yii::$app->request->post('email'),
            ]);
        }
        return $this->redirect(['index']);
    }

}
