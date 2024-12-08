<?php

namespace app\controllers;

use app\models\Content;
use Yii;

class ContentController extends Controller {

    public function actionPage($p) {
        $model = Content::findOne([
                    'url_key' => $p,
        ]);
        if (!isset($model)) {
            return $this->redirect(Yii::$app->homeUrl);
        }
        return $this->render('page', [
                    'model' => $model,
        ]);
    }

    public function actionView($id) {
        $model = Content::findOne($id);
        if (!isset($model)) {
            return $this->redirect(Yii::$app->homeUrl);
        }
        return $this->render('page', [
                    'model' => $model,
        ]);
    }

}
