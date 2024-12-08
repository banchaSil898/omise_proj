<?php

namespace app\controllers;

use app\models\Product;

class RecommendedController extends StoreController {

    public $title = 'หนังสือแนะนำ';
    public $description = 'รายชื่อหนังสือน่าสนใจ คัดสรรเพื่อนักอ่านทุกท่าน';

    public function getModel() {
        $model = new Product;
        $model->scenario = 'search';
        $model->is_hide = 0;
        $model->is_recommended = true;
        return $model;
    }

}
