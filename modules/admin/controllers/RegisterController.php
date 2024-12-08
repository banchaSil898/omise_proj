<?php

namespace app\modules\admin\controllers;

use app\models\custom\Farmer;

class RegisterController extends Controller {

    public function actionIndex() {
        
        return $this->render('index');
    }

    public function actionCreate() {
        $model = new Farmer;
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

}
