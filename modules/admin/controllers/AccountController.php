<?php

namespace app\modules\admin\controllers;

use app\models\Account;
use Yii;

class AccountController extends Controller {

    public function actionIndex() {
        $model = new Account;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Account;
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['update', 'id' => $model->id]);
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Account::findOne($id);
        $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->success();
            return $this->redirect(['update', 'id' => $model->id]);
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = Account::findOne($id);
        $model->delete();
    }

}
