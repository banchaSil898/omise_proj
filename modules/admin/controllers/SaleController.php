<?php

namespace app\modules\admin\controllers;

use app\models\Promotion;
use Yii;

class SaleController extends Controller {

    public function actionIndex() {
        $model = new Promotion;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
