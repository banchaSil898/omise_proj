<?php

namespace app\modules\admin\controllers;

use codesk\components\Controller as CodeskController;
use Yii;
use yii\filters\AccessControl;

class Controller extends CodeskController {

    public $title = 'สำนักพิมพ์มติชน';
    public $layout = '@module/views/layouts/main';

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => false,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    public function init() {
        parent::init();
    }

    public function success($body = null) {
        $options = [];
        if (isset($body)) {
            $options['body'] = $body;
        } else {
            $options['body'] = 'บันทึกข้อมูลเรียบร้อย';
        }
        Yii::$app->session->setFlash('success', $options);
    }

}
