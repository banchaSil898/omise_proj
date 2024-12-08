<?php

namespace app\controllers;

use app\models\Gift;

class GiftController extends Controller {

    public function actionExample($id) {
        $model = Gift::findOne($id);
        return $this->renderAjax('example', [
                    'model' => $model,
        ]);
    }

}
