<?php

namespace app\modules\admin\controllers;

use app\components\Html;

class AaaController extends Controller {

    public function actionIndex() {
        $data = $this->renderPartial('index');
        echo Html::content($data);
    }

}
