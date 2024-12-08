<?php

use app\components\Html;
use kartik\form\ActiveForm;
use yii\widgets\DetailView;

$form = new ActiveForm;
?>
<?php
$this->beginContent('@module/views/product/layouts/form.php', [
    'model' => $model,
]);
?>
<div class="row">
    <div class="col-sm-4">
        <div class="text-center">
            <?= Html::img($model->coverUrl, ['class' => 'img-resp book-border']); ?>          
            <div>
                <?php if ($model->currentPrice <> $model->price): ?>
                    <h3 class="book-price text-danger" style="text-decoration: line-through;margin-bottom:0;">฿ <?= Yii::$app->formatter->asDecimal(Html::encode($model->price), 2); ?></h3>
                <?php endif; ?>
                <h3 class="btn btn-lg btn-success">฿ <?= Yii::$app->formatter->asDecimal($model->currentPrice, 2); ?></h3>
            </div>
        </div>
        <?=
        DetailView::widget([
            'model' => $model,
            'options' => [
                'class' => 'table detail-view book-detail',
            ],
            'attributes' => [
                'isbn',
                'info_cover',
                'info_page',
                'info_paper',
                'info_weight',
                'info_width',
                'info_height',
                'info_publish',
            ],
        ]);
        ?>
    </div>
    <div class="col-sm-8">
        <h1 class="text-success"><?= Html::encode($model->name); ?></h1>
        <?php if (count($model->info_author)): ?>
            <h4 class="text-danger">ผู้เขียน : <?= Html::encode(implode(', ', $model->info_author)); ?></h4>
        <?php endif; ?>
        <?php if (count($model->info_translate)): ?>
            <h4 class="text-danger">แปลโดย : <?= Html::encode(implode(', ', $model->info_translate)); ?></h4>
        <?php endif; ?>
        <?php if (count($model->info_compiled)): ?>
            <h4 class="text-danger">เรียบเรียงโดย : <?= Html::encode(implode(', ', $model->info_compiled)); ?></h4>
        <?php endif; ?>
        <hr/>
        <div>
            <?= Html::content($model->brief); ?>
        </div>
        <div>
            <?= Html::content($model->description); ?>
        </div>
        <?php if (count($model->productImages)): ?>
            <hr/>
            <h3>รูปอื่นๆ</h3>
            <div>
                <?php foreach ($model->productImages as $item): ?>
                    <?= Html::a(Html::img($item->thumbUrl, ['width' => 64]), ['preview-image', 'product_id' => $item->product_id, 'image_id' => $item->image_id], ['data-modal' => 1, 'data-modal-size' => 'lg', 'class' => 'thumb-link pull-left']); ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $this->endContent(); ?>