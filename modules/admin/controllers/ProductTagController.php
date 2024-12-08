<?php

namespace app\modules\admin\controllers;

use app\models\Category;

class ProductTagController extends Controller {

    public function actionIndex() {
        
        return $this->render('index', [
                  'dataProvider'=> $dataProvider,
        ]);
    }

}
