<?php

namespace app\controllers;

use app\models\Purchase;
use Yii;

class TestController extends Controller {

    public $title = 'ทดสอบ';

    public function actionIndex() {
        $this->layout = '@vendor/codesk/yii2-ext/themes/unishop/views/layouts/html';
        return $this->render('index');
    }

    public function actionReceipt() {
        return Purchase::find()->orderBy(['created_at' => SORT_DESC])->one()->getPdf();
    }

}
