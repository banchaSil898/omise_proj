<?php

namespace app\controllers;

use app\models\Folder;
use app\models\LogSearch;
use app\models\Product;
use Yii;
use yii\helpers\ArrayHelper;

class CategoryController extends Controller {

    public function actionIndex($id = null, $text = null) {

        $user = Yii::$app->user->identity;

        $folder = Folder::findOne($id);

        $model = new Product;
        $model->scenario = 'search';
        $model->is_hide = 0;
        if (isset($text)) {
            $model->search['text'] = $text;
        }
        if (isset($folder)) {
            $model->search['folder_id'] = $folder->id;
        }
        $dataProvider = $model->search();

        LogSearch::doSaveLog($text, $dataProvider->totalCount, ArrayHelper::getValue($user, 'id'));

        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
                    'oCategory' => $folder,
        ]);
    }

    public function actionRecommend() {
        $model = new Product;
        $model->category_id = $category->id;
        $model->is_hide = 0;
        if (isset($categoryItem)) {
            $model->category_item_id = $categoryItem->id;
        }
        $dataProvider = $model->search();
        return $this->render('recommend', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

}
