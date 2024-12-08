<?php

namespace app\modules\admin\controllers;

use app\models\Coupon;
use app\models\CouponGroup;
use kartik\form\ActiveForm;
use Yii;
use yii\web\Response;

class CouponGroupController extends Controller {

    public function actionIndex() {
        $model = new CouponGroup;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new CouponGroup;
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        for ($i = 0; $i < $model->usage_max; $i++) {
                            $coupon = new Coupon;
                            $coupon->code_group_id = $model->id;
                            $coupon->discount_type = $model->discount_type;
                            $coupon->discount_value = $model->discount_value;
                            $coupon->valid_date = $model->valid_date;
                            $coupon->expire_date = $model->expire_date;
                            $coupon->usage_max = $model->usage_max;
                            $coupon->usage_current = 0;
                            $coupon->save();
                        }
                        if (!Yii::$app->request->isAjax) {
                            return $this->redirect(['index']);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('create', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = CouponGroup::findOne($id);
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionView($id) {
        $model = CouponGroup::findOne($id);
        return $this->render('view', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = CouponGroup::findOne($id);
        $model->delete();
    }

}
