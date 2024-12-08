<?php

namespace app\widgets;

use app\models\Folder;
use codesk\components\Html;
use kartik\form\ActiveForm;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\widgets\Menu;

class Breadcrumbs extends Widget {

    public $showHome = true;
    public $enableSearch = true;
    public $items = [];

    public function init() {
        parent::init();
        if ($this->showHome) {
            $baseItems = [
                [
                    'label' => 'หน้าแรก', 'url' => Yii::$app->homeUrl
                ],
            ];
        } else {
            $baseItems = [];
        }
        $baseItems = array_merge($baseItems, $this->items);
        echo Html::beginTag('div', ['class' => 'breadcrumb-container']);
        echo Html::beginTag('div', ['class' => 'container']);

        echo Menu::widget([
            'items' => $baseItems,
            'options' => [
                'class' => 'breadcrumb pull-left',
            ],
        ]);

        if ($this->enableSearch) {
            $form = ActiveForm::begin([
                        'id' => 'nav-search-frm',
                        'action' => ['category/index'],
                        'method' => 'get',
                        'type' => 'inline',
                        'options' => [
                            'style' => 'margin:5px;',
                            'class' => 'pull-right search-bar-frm',
                        ],
            ]);
            echo Html::input('text', 'text', Yii::$app->request->get('text'), ['class' => 'form-control search-bar-input', 'placeholder' => 'ค้นหาหนังสือ...', 'autofocus' => true, 'onclick' => '$(this).select()']);
            echo Html::beginTag('div', ['class' => 'input-group']);
            echo Html::dropDownList('id', Yii::$app->request->get('id'), ArrayHelper::map(Folder::find()->defaultScope()->all(), 'id', 'name'), ['class' => 'selectpicker', 'prompt' => '(ทุกหมวดหมู่)']);
            echo Html::submitButton(Html::awesome('search', ['class' => 'icon']), ['class' => 'btn btn-primary search-button']);
            echo Html::endTag('div');
            ActiveForm::end();
            /*
              echo Html::beginTag('ul', ['class' => 'list-unstyled search-box pull-right']);
              echo Html::beginTag('li', ['data-target' => '#search', 'data-toggle' => 'sub-header']);
              echo Html::button(Html::awesome('search', ['class' => 'icon']), ['class' => 'btn btn-primary-dark search-button']);

              echo Html::beginTag('div', ['class' => 'row search-action sub-header', 'id' => 'search']);

              echo Html::beginTag('div', ['class' => 'col-sm-8 col-xs-12 no-padding-right']);
              echo Html::beginTag('div', ['class' => 'input-group']);

              echo Html::tag('span', Html::button(Html::awesome('search', ['class' => 'icon']), ['class' => 'btn btn-search']), ['class' => 'input-group-btn']);
              echo Html::input('text', '', '', ['class' => 'form-control search-book', 'placeholder' => 'ค้นหาหนังสือ...']);

              echo Html::endTag('div');
              echo Html::endTag('div');

              echo Html::beginTag('div', ['class' => 'col-sm-4 col-xs-12 select-wrapper', 'style' => 'padding:0px;']);
              echo Html::dropDownList('id', null, ArrayHelper::map(Category::find()->orderBy('order_no')->all(), 'id', 'name'), ['class' => 'selectpicker', 'id' => 'id_select', 'prompt' => '(ค้นหาจากทุกหมวดหมู่)']);
             */
            /*
              <option selected>All Category</option>
              <option>Books</option>
              <option>Textbooks</option>
              <option>Audiobooks</option>
              <option>Magazines</option>
              <option>Kids</option> */

            echo Html::endTag('div');
            echo Html::endTag('div');
            echo Html::endTag('li');
            echo Html::endTag('ul');
        }
    }

    public function run() {
        echo Html::endTag('div');
        echo Html::endTag('div');
    }

}
