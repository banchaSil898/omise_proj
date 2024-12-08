<?php

namespace app\controllers;

use app\models\Product;

class BestsellerController extends StoreController {

    public $title = 'หนังสือขายดี';
    public $description = 'รายชื่อหนังสือขายดี';

    public function getModel() {
        $model = new Product;
        $model->scenario = 'search';
        $model->is_bestseller = true;
        $model->is_hide = 0;
        return $model;
    }

}
