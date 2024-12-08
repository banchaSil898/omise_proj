<?php

use app\widgets\MicModule;
use codesk\components\Html;
use yii\web\View;

/* @var $this View */
?>
<div class="wow fadeInUp">
    <?php
    MicModule::begin([
        'parentId' => $id,
        'title' => $title,
        'subtitle' => $subtitle,
        'buttons' => [
            [
                'label' => Html::tag('i', '', ['class' => 'icon fa fa-caret-left']),
                'url' => '#',
                'options' => [
                    'class' => 'btn btn-navigation left-nav-arrow-featured owl-prev',
                    'data' => [
                        'target' => '#' . $id . '-carousel',
                    ],
                ],
            ],
            [
                'label' => Html::tag('i', '', ['class' => 'icon fa fa-caret-right']),
                'url' => '#',
                'options' => [
                    'class' => 'btn btn-navigation right-nav-arrow-featured owl-next',
                    'data' => [
                        'target' => '#' . $id . '-carousel',
                    ],
                ],
            ],
        ],
    ]);
    ?>
    <div class="<?= $widget->type; ?>">
        <div id="<?= $id; ?>-carousel" class="owl-carousel">
            <?php foreach ($dataProvider->models as $item): ?>
                <?=
                $this->render($widget->itemView, [
                    'item' => $item,
                    'options' => $itemOptions,
                ]);
                ?>
            <?php endforeach; ?>
        </div>
    </div>
    <?php MicModule::end(); ?>
</div>
<?php
$this->registerJs(<<<JS
    $("#$id-carousel").owlCarousel({
        items : 5,
        itemsMobile :[480,2],
        itemsDesktopSmall : [980,2],
        itemsDesktop :   [1199,3]
    });
JS
);
?>