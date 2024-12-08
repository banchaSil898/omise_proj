<?php

namespace app\controllers;

use app\models\Product;
use app\models\Slide;
use Yii;
use yii\helpers\ArrayHelper;

class HighlightController extends StoreController {

    public $title = 'โปรโมชั่น';
    public $description = 'รายชื่อหนังสือโปรโมชั่น';

    public function getModel() {
        $model = new Product;
        $model->scenario = 'search';
        $model->is_hide = 0;
        return $model;
    }

    public function actionView($id) {
        $slide = Slide::findOne($id);
        if ($slide->slide_type !== Slide::TYPE_BUNDLE) {
            return $this->redirect(Yii::$app->homeUrl);
        }
        $model = $this->getModel();
        $dataProvider = $model->search();
        $dataProvider->query->andWhere(['IN', 'id', array_keys(ArrayHelper::map($slide->getSlideProducts()->all(), 'product_id', 'product_id'))]);
        return $this->render('view', [
                    'slide' => $slide,
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
