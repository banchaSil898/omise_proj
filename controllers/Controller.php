<?php

namespace app\controllers;

use app\components\Cart;
use app\components\Html;
use app\models\Content;
use Yii;
use yii\web\Controller as BaseController;

class Controller extends BaseController {

    public $title;
    public $showSubFooter = false;

    public function init() {
        parent::init();
    }

    /**
     * 
     * @return Cart
     */
    public function getCart() {
        $cart = Yii::$app->session->get('cart');
        if (!isset($cart)) {
            Yii::$app->session->set('cart', new Cart);
            $cart = Yii::$app->session->get('cart');
        }
        return $cart;
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

    public function getMenuTop() {
        $ret = [
            [
                'label' => Html::awesome('home') . ' หน้าแรก',
                'url' => ['/'],
                'active' => $this->id === 'site' && $this->action->id === 'index',
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
            [
                'label' => Html::awesome('dollar') . ' วิธีการชำระเงิน',
                'url' => ['/site/term'],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ],
        ];

        $items = Content::find()->where([
                    'content_type' => Content::TYPE_ARTICLE,
                ])
                ->orderBy([
                    'order_no' => SORT_ASC,
                ])
                ->all();
        foreach ($items as $item) {
            $ret[] = [
                'label' => ($item->icon ? Html::tag('span', '', ['class' => 'fa ' . $item->icon]) : '') . ' ' . Html::encode($item->name),
                'url' => ['/content/page', 'p' => Html::encode($item->url_key)],
                'linkOptions' => [
                    'data-pjax' => 0,
                ],
            ];
        }

        $ret[] = [
            'label' => Html::awesome('phone') . ' ติดต่อเรา',
            'url' => ['/site/contact'],
            'linkOptions' => [
                'data-pjax' => 0,
            ],
        ];
        return $ret;
    }

}
