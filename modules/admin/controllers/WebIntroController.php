<?php

namespace app\modules\admin\controllers;

use app\components\Intro;
use app\modules\admin\components\UploadImageAction;
use Yii;

class WebIntroController extends Controller {

    public function actions() {
        return [
            'upload-file' => [
                'class' => UploadImageAction::className(),
                'model' => Intro::className(),
                'attribute' => 'background_file',
                'uploadUrl' => Intro::getUploadUrl(),
                'uploadPath' => Intro::getUploadPath(),
                'sizes' => [
                    'default' => [
                        'extension' => 'jpg',
                    ],
                ],
            ],
        ];
    }

    public function actionIndex() {
        $model = new Intro();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['index']);
            }
        }
        return $this->render('index', [
                    'model' => $model,
        ]);
    }

    public function actionDelete() {
        $model = new Intro();
        $model->delete();
        $this->success();
        return $this->redirect(['index']);
    }

}
