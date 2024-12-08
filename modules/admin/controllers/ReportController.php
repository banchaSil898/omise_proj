<?php

namespace app\modules\admin\controllers;

use app\models\custom\Farmer;

class ReportController extends Controller {

    public function actionIndex() {

        return $this->render('index');
    }

    public function actionPlant() {

        return $this->render('plant');
    }

}
