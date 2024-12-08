<?php

namespace app\controllers;

use app\models\Member;
use Yii;
use yii\web\HttpException;

class ForgotController extends Controller {

    public function actionIndex() {
        $model = new Member;
        $model->scenario = 'forgot';

        if ($model->load(Yii::$app->request->post())) {
            if ($model->doForgot()) {
                return $this->redirect(['done', 'uid' => base64_encode($model->username)]);
            }
        }
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionDone($uid) {
        return $this->render('done', [
                    'email' => base64_decode($uid),
        ]);
    }

    public function actionReset($key, $code) {
        $model = Member::findOne([
                    'account_key' => $key,
                    'forgot_code' => $code,
        ]);
        if (!isset($model)) {
            throw new HttpException('ไม่สามารถทำรายการได้ กรุณาติดต่อเจ้าหน้าที่', 404);
        }

        $model->scenario = 'reset';
        if ($model->load(Yii::$app->request->post())) {
            if ($model->doReset()) {
                $this->success('เปลี่ยนรหัสผ่านเรียบร้อย');
                Yii::$app->user->login($model);
                return $this->redirect(Yii::$app->homeUrl);
            }
        }

        return $this->render('reset', [
                    'model' => $model,
        ]);
    }

}
