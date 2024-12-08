<?php

namespace app\modules\admin\controllers;

use app\models\Content;
use app\modules\admin\components\UploadImageAction;
use Yii;
use yii\helpers\Url;
use yii\web\HttpException;

class ContentController extends Controller {

    public $title = 'จัดการเนื้อหาเว็บไซต์';
    public $subtitle = '';
    public $type = 'content';

    public function actions() {
        return [
            'upload-file' => [
                'class' => UploadImageAction::className(),
                'model' => Content::className(),
                'attribute' => 'background_file',
                'uploadUrl' => Content::getUploadUrl(),
                'uploadPath' => Content::getUploadPath(),
                'sizes' => [
                    'default' => [
                        'extension' => 'jpg',
                    ],
                ],
            ],
        ];
    }

    public function init() {
        parent::init();
        if ($this->id === 'content') {
            throw new HttpException('ไม่สามารถเรียกใช้งาน content โดยตรงได้', 500);
        }
    }

    public function actionIndex() {
        $model = new Content;
        $model->load(Yii::$app->request->get());
        $model->content_type = $this->type;
        $dataProvider = $model->search();

        Url::remember('', 'content');

        return $this->render('/content/index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Content;
        $model->content_type = $this->type;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(Url::previous('content') ? Url::previous('content') : ['index']);
            }
        }
        return $this->render('/content/form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Content::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(Url::previous('content') ? Url::previous('content') : ['index']);
            }
        }
        return $this->render('/content/form', [
                    'model' => $model,
        ]);
    }

    public function actionView($id) {
        $model = Content::findOne($id);
        return $this->render('/content/view', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Content::findOne($id);
        if ($model->delete()) {
            
        }
    }

    public function actionOrderUp($id) {
         $model = Content::findOne($id);
         $model->doMoveUp();
    }

    public function actionOrderDown($id) {
         $model = Content::findOne($id);
         $model->doMoveDown();        
    }

}
