<?php

namespace app\modules\admin\controllers;

use app\components\Html;
use app\models\Gift;
use app\models\GiftItemSelector;
use app\models\GiftProductSelector;
use app\models\ItemSelector;
use app\models\Product;
use app\models\ProductSelector;
use app\models\Promotion;
use app\models\PromotionCoupon;
use app\models\PromotionGift;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\Response;

class PromotionController extends Controller {

    public function actionIndex() {
        $model = new Promotion;
        $model->scenario = 'search';
        $model->load(Yii::$app->request->get());
        $dataProvider = $model->search();
        $dataProvider->sort = [
            'defaultOrder' => [
                'is_active' => SORT_DESC,
                'date_start' => SORT_DESC,
            ],
        ];
        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $model = new Promotion;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Promotion::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update', 'id' => $model->id]);
            }
        }
        return $this->render('form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateCoupon($id) {
        $model = Promotion::findOne($id);

        $coupon = new PromotionCoupon;
        $coupon->promotion_id = $model->id;
        $dataProvider = $coupon->search();

        return $this->render('form-coupon', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdateCondition($id) {
        $model = Promotion::findOne($id);
        $manager = $model->getPromotionManager();

        if ($manager->load(Yii::$app->request->post())) {
            if ($manager->save()) {
                $this->success();
                return $this->redirect(['update-condition', 'id' => $model->id]);
            }
        }

        return $this->render('form-condition', [
                    'model' => $model,
                    'manager' => $manager,
        ]);
    }

    public function actionUpdateDiscountPercent($id) {
        $model = Promotion::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-discount-percent', 'id' => $model->id]);
            }
        }
        return $this->render('form-discount-percent', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateDiscountAmount($id) {
        $model = Promotion::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                $this->success();
                return $this->redirect(['update-discount-amount', 'id' => $model->id]);
            }
        }
        return $this->render('form-discount-amount', [
                    'model' => $model,
        ]);
    }

    public function actionUpdateCategory($id) {
        $model = Promotion::findOne($id);
        if (Yii::$app->request->isPost) {
            $items = Yii::$app->request->post('items', []);
            $model->saveCategoryItems($items);
            return $this->redirect(['update-category', 'id' => $model->id]);
        }
        $categoryItems = $model->getPromotionFolders()->indexBy('folder_id')->asArray()->all();
        return $this->render('form-category', [
                    'model' => $model,
                    'categoryItems' => $categoryItems,
        ]);
    }

    public function actionDelete($id) {
        $model = Promotion::findOne($id);
        if ($model->delete()) {
            
        }
    }

    public function actionToggleStatus($id, $attribute) {
        $model = Promotion::findOne($id);
        if ($model->{$attribute}) {
            $model->{$attribute} = 0;
        } else {
            $model->{$attribute} = 1;
        }
        $model->save();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionUpdateProduct($id) {
        $model = Promotion::findOne($id);

        $item = new ItemSelector;
        $item->scenario = 'search';
        $item->load(Yii::$app->request->get());
        $itemProvider = $item->search();
        $itemProvider->query->andWhere(['IN', 'id', array_values(ArrayHelper::map($model->promotionProducts, 'product_id', 'product_id'))]);
        $itemProvider->pagination->pageSize = 10;

        $product = new ProductSelector;
        $product->scenario = 'search';
        $product->load(Yii::$app->request->get());
        $productProvider = $product->search();
        $productProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->promotionProducts, 'product_id', 'product_id'))]);
        $productProvider->pagination->pageSize = 10;
        return $this->render('form-product', [
                    'model' => $model,
                    'product' => $product,
                    'item' => $item,
                    'itemProvider' => $itemProvider,
                    'productProvider' => $productProvider,
        ]);
    }

    public function actionUpdateItem($id) {
        $model = Promotion::findOne($id);

        $item = new ItemSelector;
        $item->scenario = 'search';
        $item->load(Yii::$app->request->get());
        $itemProvider = $item->search();
        $itemProvider->query->andWhere(['IN', 'id', array_values(ArrayHelper::map($model->promotionItems, 'product_id', 'product_id'))]);
        $itemProvider->pagination->pageSize = 10;

        $product = new ProductSelector;
        $product->scenario = 'search';
        $product->load(Yii::$app->request->get());
        $productProvider = $product->search();
        $productProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->promotionItems, 'product_id', 'product_id'))]);
        $productProvider->pagination->pageSize = 10;

        switch ($model->promotion_type) {
            case Promotion::TYPE_PRICE_GET_THING:
                $title = 'ของแถม';
                break;
            default:
                $title = 'สินค้าลดราคา';
                break;
        }


        return $this->render('form-item', [
                    'model' => $model,
                    'product' => $product,
                    'item' => $item,
                    'itemProvider' => $itemProvider,
                    'productProvider' => $productProvider,
                    'title' => $title,
        ]);
    }

    public function actionUpdateGift($id) {
        $model = Promotion::findOne($id);

        $item = new GiftItemSelector;
        $item->scenario = 'search';
        $item->load(Yii::$app->request->get());
        $itemProvider = $item->search();
        $itemProvider->query->andWhere(['IN', 'id', array_values(ArrayHelper::map($model->promotionGifts, 'gift_id', 'gift_id'))]);
        $itemProvider->pagination->pageSize = 10;

        $product = new GiftProductSelector;
        $product->scenario = 'search';
        $product->load(Yii::$app->request->get());
        $productProvider = $product->search();
        $productProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->promotionGifts, 'gift_id', 'gift_id'))]);
        $productProvider->pagination->pageSize = 10;
        return $this->render('form-gift', [
                    'model' => $model,
                    'product' => $product,
                    'item' => $item,
                    'itemProvider' => $itemProvider,
                    'productProvider' => $productProvider,
        ]);
    }

    public function actionUpdateGiftRule($id) {
        $model = Promotion::findOne($id);

        if (Yii::$app->request->post('hasEditable')) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $ret = [];
            $itemId = Json::decode(Yii::$app->request->post('editableKey'));
            $model = PromotionGift::findOne($itemId);

            $data = current(Yii::$app->request->post('PromotionGift'));
            $model->setAttributes($data);
            if ($model->save()) {
                return ['output' => Yii::$app->formatter->asDecimal($model->buy_rate, 2)];
            } else {
                return ['message' => Html::errorSummary($model)];
            }
        }

        $item = new PromotionGift;
        $item->scenario = 'search';
        $item->promotion_id = $model->id;
        $item->load(Yii::$app->request->get());
        $dataProvider = $item->search();

        return $this->render('form-gift-rule', [
                    'model' => $model,
                    'item' => $item,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProductImport($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Promotion::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            parse_str(ArrayHelper::getValue($data, 'condition'), $condition);
            $product = new Product;
            $product->scenario = 'search';
            $product->attributes = $condition['ProductSelector'];
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $dataProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->promotionProducts, 'product_id', 'product_id'))]);
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->productAdd($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->productAdd($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

    public function actionProductRemove($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Promotion::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            $product = new Product;
            $product->scenario = 'search';
            $product->search['text'] = ArrayHelper::getValue($data, 'text');
            $product->search['folder_id'] = ArrayHelper::getValue($data, 'folder_id');
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->productRemove($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->productRemove($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

    public function actionOrderUp($id) {
        $model = Promotion::findOne($id);
        $model->doMoveUp();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionOrderDown($id) {
        $model = Promotion::findOne($id);
        $model->doMoveDown();
        if (!Yii::$app->request->isAjax) {
            return $this->redirect(['index']);
        }
    }

    public function actionItemImport($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Promotion::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            $product = new Product;
            $product->scenario = 'search';
            $product->search['text'] = ArrayHelper::getValue($data, 'text');
            $product->search['folder_id'] = ArrayHelper::getValue($data, 'folder_id');
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $dataProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->promotionItems, 'product_id', 'product_id'))]);
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->itemAdd($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->itemAdd($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

    public function actionItemRemove($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Promotion::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            $product = new Product;
            $product->scenario = 'search';
            $product->search['text'] = ArrayHelper::getValue($data, 'text');
            $product->search['folder_id'] = ArrayHelper::getValue($data, 'folder_id');
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->itemRemove($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->itemRemove($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

    public function actionGiftImport($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Promotion::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            $product = new Gift;
            $product->scenario = 'search';
            $product->search['text'] = ArrayHelper::getValue($data, 'text');
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $dataProvider->query->andWhere(['NOT IN', 'id', array_values(ArrayHelper::map($model->promotionGifts, 'gift_id', 'gift_id'))]);
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->giftAdd($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->giftAdd($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

    public function actionGiftRemove($id) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Promotion::findOne($id);

        $data = Yii::$app->request->post();
        $is_all = (int) ArrayHelper::getValue($data, 'all');
        if ($is_all) {
            $product = new Gift;
            $product->scenario = 'search';
            $product->search['text'] = ArrayHelper::getValue($data, 'text');
            $dataProvider = $product->search();
            $dataProvider->pagination = false;
            $items = array_keys(ArrayHelper::map($dataProvider->models, 'id', 'id'));
            if (count($items)) {
                return $model->giftRemove($items);
            }
        } else {
            $items = explode(',', ArrayHelper::getValue($data, 'items'));
            if (count($items)) {
                return $model->giftRemove($items);
            }
        }
        throw new HttpException(500, 'Can not add items to promotion.');
    }

}
