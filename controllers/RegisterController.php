<?php

namespace app\controllers;

use app\models\Member;
use Yii;
use yii\web\HttpException;

class RegisterController extends Controller {

    public function actionIndex($url = null) {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(Yii::$app->homeUrl);
        }

        if (isset($url)) {
            Yii::$app->user->setReturnUrl($url);
        }

        $model = new Member;
        $model->scenario = 'register';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->doRegister()) {
                return $this->redirect(['confirm', 'email' => $model->username]);
            }
        }
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionConfirm($email = null) {
        $model = new Member;
        $model->scenario = 'resend';

        if ($email) {
            return $this->render('confirm', [
                        'model' => $model,
                        'email' => $email,
            ]);
        } else {
            if ($model->load(Yii::$app->request->post()) && $model->doResend()) {
                return $this->redirect(['confirm', 'email' => $model->username]);
            }
            return $this->render('resend', [
                        'model' => $model,
                        'email' => $email,
            ]);
        }
    }

    public function actionActivate($key, $code) {
        $model = Member::findOne([
                    'account_key' => $key,
                    'active_code' => $code,
        ]);
        if (!isset($model)) {
            $this->success('ไม่สามารถทำรายการได้ กรุณาติดต่อเจ้าหน้าที่');
            return $this->redirect(['confirm']);
        }
        $model->scenario = 'active';
        if ($model->doActive()) {
            Yii::$app->user->login($model);
            $this->success('เปิดใช้งานบัญชีของคุณเรียบร้อย');
            return $this->redirect(['my/index']);
        }
        return $this->redirect(['confirm', 'email' => $model->username]);
    }

}
