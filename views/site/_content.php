<?php

use app\components\Html;
use app\models\Content;

/* @var $model Content */
?>
<div class="col-md-6">
    <div class="content-box">
        <div class="row">
            <div class="col-sm-4 text-center">
                <?=
                Html::a(Html::img('@web/images/blank.gif', [
                            'data-echo' => $model->backgroundUrl,
                            'class' => 'text-center',
                            'style' => 'max-height:150px;max-width:100%;'
                        ]), ['content/view', 'id' => $model->id]);
                ?>        
            </div>
            <div class="col-sm-8">
                <h3 class="news-name">
                    <?= Html::a(Html::encode($model->name), ['content/view', 'id' => $model->id]); ?>
                </h3>
                <div>
                    <?= Html::encode($model->brief); ?>
                </div>
            </div>
        </div>
    </div>
</div>