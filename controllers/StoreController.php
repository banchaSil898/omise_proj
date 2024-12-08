<?php

namespace app\controllers;

use app\models\Product;

class StoreController extends Controller {

    public $title;
    public $description;

    public function actionIndex() {
        $model = $this->getModel();
        $dataProvider = $model->search();
        
        return $this->render('/store/index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function getModel() {
        $model = new Product;
        $model->scenario = 'search';
        $model->is_hide = 0;
        return $model;
    }

}
