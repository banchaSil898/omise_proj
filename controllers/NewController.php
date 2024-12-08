<?php

namespace app\controllers;

use app\models\Product;

class NewController extends StoreController {

    public $title = 'หนังสือสำนักพิมพ์มติชน';
    public $description = 'รายชื่อหนังสือสำนักพิมพ์มติชน';

    public function getModel() {
        $model = new Product;
        $model->scenario = 'search';
        $model->is_hide = 0;
        $model->is_new = true;
        return $model;
    }

}
