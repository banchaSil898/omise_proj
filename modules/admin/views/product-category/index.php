<?php

use codesk\components\Html;
use yii\widgets\Pjax;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => 'จัดการหมวดหมู่สินค้า',
    'icon' => 'cubes',
    'tools' => [
        [
            'encode' => false,
            'label' => Html::icon('plus') . ' เพิ่มหมวดหมู่',
            'url' => ['create'],
            'linkOptions' => [
                'class' => 'btn btn-primary',
                'data-modal' => 1,
            ],
        ]
    ],
]);
?>
<?php
Pjax::begin([
    'id' => 'pjax-page',
]);
?>
<div class="table-responsive">
    <table class="table table-bordered">
        <colgroup>
            <col width="50"/>
            <col width="50"/>
            <col/>
            <col width="50"/>
            <col width="100"/>
            <col width="120"/>
        </colgroup>
        <thead>
            <tr class="bg-primary">
                <th class="text-center">ลำดับ</th>
                <th class="text-center" colspan="2">ชื่อหมวด</th>
                <th class="text-center">สินค้า</th>
                <th class="text-center">ลำดับ</th>
                <th class="text-center">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!count($categories)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted">ไม่มีรายการใดๆ</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($categories as $categoryOrder => $category) : ?>
                <tr class="bg-grey">
                    <td class="text-center text-bold"><?= Html::encode($categoryOrder + 1); ?></td>
                    <td class="text-left text-bold" colspan="2">
                        <?=
                        Html::a(Html::encode($category->name), ['product/index', 'Product' => [
                                'search' => [
                                    'only_folder_id' => $category->id,
                                ],
                        ]]);
                        ?>      
                    </td>
                    <td class="text-center">
                        <?=
                        Html::a(Html::encode($category->getProductFolders()->count()), ['product/index', 'Product' => [
                                'search' => [
                                    'only_folder_id' => $category->id,
                                ],
                            ]], [
                            'data-pjax' => 0,
                        ]);
                        ?>
                    </td>
                    <td class="text-center">
                        <?=
                        Html::a(Html::icon('arrow-down'), ['product-category/move-down', 'id' => $category->id], [
                            'title' => 'เลื่อนลง',
                            'data-pjax' => 0,
                            'data-ajax' => 1,
                            'data-ajax-method' => 'post',
                            'data-ajax-pjax-reload' => '#pjax-page',
                        ]);
                        ?>
                        <?=
                        Html::a(Html::icon('arrow-up'), ['product-category/move-up', 'id' => $category->id], [
                            'title' => 'เลื่อนขึ้น',
                            'data-pjax' => 0,
                            'data-ajax' => 1,
                            'data-ajax-method' => 'post',
                            'data-ajax-pjax-reload' => '#pjax-page',
                        ]);
                        ?>
                    </td>
                    <td class="text-right">
                        <?=
                        Html::a(Html::icon('plus'), ['product-category-item/create', 'id' => $category->id], [
                            'title' => 'เพิ่มหมวดย่อย',
                            'data-modal' => 1,
                            'data-modal-size' => 'lg',
                            'data-pjax' => 0
                        ]);
                        ?>
                        <?=
                        Html::a(Html::icon('pencil'), ['product-category/update', 'id' => $category->id], [
                            'title' => 'แก้ไข',
                            'data-modal' => 1,
                            'data-modal-size' => 'lg',
                            'data-pjax' => 0
                        ]);
                        ?>
                        <?php if ($category->isDeletable): ?>
                            <?=
                            Html::a(Html::icon('trash'), ['product-category/delete', 'id' => $category->id], [
                                'title' => 'ลบข้อมูล',
                                'data-ajax-confirm' => 'ต้องการลบรายการนี้?',
                                'data-pjax' => 0,
                                'data-ajax' => 1,
                                'data-ajax-method' => 'post',
                                'data-ajax-pjax-reload' => '#pjax-page',
                            ]);
                            ?>
                        <?php else: ?>
                            <?= Html::tag('span', Html::icon('trash'), ['class' => 'text-blur']) ?>
                        <?php endif; ?>
                    </td>
                </tr>        
                <?php foreach ($category->getCategoryItems()->orderBy(['position' => SORT_ASC])->all() as $itemOrder => $item) : ?>
                    <tr class="service-row">
                        <td class="text-center"></td>
                        <td class="text-center"><?= Html::encode($categoryOrder + 1); ?>.<?= Html::encode($itemOrder + 1); ?></td>
                        <td class="service-title">
                            <?=
                            Html::a(Html::encode($item->name), ['product/index', 'Product' => [
                                    'search' => [
                                        'category_id' => $category->id,
                                        'category_item_id' => $item->id,
                                    ],
                            ]]);
                            ?>      
                        </td>
                        <td class="text-center">
                            <?=
                            Html::a(Html::encode($item->getProductFolders()->count()), ['product/index', 'Product' => [
                                    'search' => [
                                        'only_folder_id' => $item->id,
                                    ],
                                ]], [
                                'data-pjax' => 0,
                            ]);
                            ?>
                        </td>
                        <td class="text-center">
                            <?=
                            Html::a(Html::icon('arrow-down'), ['product-category/move-down', 'id' => $item->id], [
                                'title' => 'เลื่อนลง',
                                'data-pjax' => 0,
                                'data-ajax' => 1,
                                'data-ajax-method' => 'post',
                                'data-ajax-pjax-reload' => '#pjax-page',
                            ]);
                            ?>
                            <?=
                            Html::a(Html::icon('arrow-up'), ['product-category/move-up', 'id' => $item->id], [
                                'title' => 'เลื่อนขึ้น',
                                'data-pjax' => 0,
                                'data-ajax' => 1,
                                'data-ajax-method' => 'post',
                                'data-ajax-pjax-reload' => '#pjax-page',
                            ]);
                            ?>
                        </td>
                        <td class="text-right">
                            <?=
                            Html::a(Html::icon('pencil'), ['product-category-item/update', 'id' => $item->id], [
                                'title' => 'แก้ไข',
                                'data-modal' => 1,
                                'data-modal-size' => 'lg',
                                'data-pjax' => 0
                            ]);
                            ?>
                            <?php if ($item->isDeletable): ?>
                                <?=
                                Html::a(Html::icon('trash'), ['product-category-item/delete', 'id' => $item->id], [
                                    'title' => 'ลบข้อมูล',
                                    'data-ajax-confirm' => 'ต้องการลบรายการนี้?',
                                    'data-pjax' => 0,
                                    'data-ajax' => 1,
                                    'data-ajax-method' => 'post',
                                    'data-ajax-pjax-reload' => '#pjax-page',
                                ]);
                                ?>
                            <?php else: ?>
                                <?= Html::tag('span', Html::icon('trash'), ['class' => 'text-blur']) ?>
                            <?php endif; ?>
                        </td>
                    </tr>    
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php Pjax::end(); ?>
<?php Panel::end(); ?>