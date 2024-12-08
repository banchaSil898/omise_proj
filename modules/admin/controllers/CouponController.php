<?php

namespace app\modules\admin\controllers;

use app\models\Promotion;
use app\models\PromotionCoupon;
use app\models\PromotionCouponUsage;
use kartik\form\ActiveForm;
use Yii;
use yii\web\Response;

class CouponController extends Controller {

    public function actionCreate($id) {
        $promotion = Promotion::findOne($id);

        $model = new PromotionCoupon;
        $model->scenario = 'create';
        $model->usage_current = 0;
        $model->promotion_id = $promotion->id;
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        if (!Yii::$app->request->isAjax) {
                            $this->success();
                            return $this->redirect(['product/update-coupon', 'id' => $promotion->id]);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('form', [
                    'promotion' => $promotion,
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = PromotionCoupon::findOne($id);
        $model->scenario = 'create';
        $promotion = $model->promotion;
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->save()) {
                        if (!Yii::$app->request->isAjax) {
                            $this->success();
                            return $this->redirect(['product/update-coupon', 'id' => $promotion->id]);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('form', [
                    'promotion' => $promotion,
                    'model' => $model,
        ]);
    }

    public function actionCreateMultiple($id) {
        $promotion = Promotion::findOne($id);

        $model = new PromotionCoupon;
        $model->scenario = 'create-multiple';
        $model->usage_current = 0;
        $model->promotion_id = $promotion->id;
        if ($model->load(Yii::$app->request->post())) {
            switch (Yii::$app->request->get('mode')) {
                case 'validate':
                    Yii::$app->response->format = Response::FORMAT_JSON;
                    return ActiveForm::validate($model);
                default:
                    if ($model->createMultiple()) {
                        if (!Yii::$app->request->isAjax) {
                            $this->success();
                            return $this->redirect(['product/update-coupon', 'id' => $promotion->id]);
                        }
                    }
                    break;
            }
        }
        return $this->renderAjax('form-multiple', [
                    'promotion' => $promotion,
                    'model' => $model,
        ]);
    }

    public function actionDelete($id) {
        $model = PromotionCoupon::findOne($id);
        $model->delete();
    }

    public function actionDeleteAll($id) {
        $model = Promotion::findOne($id);
        PromotionCouponUsage::deleteAll([
            'promotion_id' => $model->id,
        ]);
        PromotionCoupon::deleteAll([
            'promotion_id' => $model->id,
        ]);
    }

}
