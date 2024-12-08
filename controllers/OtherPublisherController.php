<?php

namespace app\controllers;

use app\models\Product;

class OtherPublisherController extends StoreController {

    public $title = 'หนังสือจากเพื่อนสำนักพิมพ์';
    public $description = 'หนังสือจากเพื่อนสำนักพิมพ์ที่น่าสนใจ';

    public function actionIndex() {
        $model = $this->getModel();
        $dataProvider = $model->search();
        $dataProvider->query->andWhere(['<>', 'is_own', 1]);
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
