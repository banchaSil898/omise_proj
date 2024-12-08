<?php

use app\models\Folder;
use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
?>
<?php
$form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['category/index'],
        ]);
?>
<div id="slider-search" class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="slider-action">        
                <div class="row sub-header" id="search">
                    <div class='col-md-12'>
                        <div class="row">
                            <div class="col-sm-8 no-padding-right">
                                <div class="input-group">
                                    <span class="input-group-btn"><button class="btn btn-search" type="button"><i class="fa fa-search icon"></i></button></span>
                                    <?=
                                    Html::textInput('text', '', [
                                        'class' => 'form-control search-book',
                                        'placeholder' => 'ค้นหา...',
                                        'autofocus' => true,
                                    ]);
                                    ?>
                                </div><!-- /.input-group -->
                            </div><!-- /.col -->

                            <div class="select-wrapper col-sm-4" style="padding:0px;">
                                <?=
                                Html::dropDownList('id', null, ArrayHelper::map(Folder::find()->defaultScope()->all(), 'id', 'name'), [
                                    'id' => 'id_select',
                                    'class' => 'selectpicker',
                                    'prompt' => '(ค้นหาจากทุกหมวดหมู่)',
                                ]);
                                ?>
                            </div><!-- /.col -->
                        </div><!-- /.row -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.slider-action -->  
        </div><!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.container -->
<?php ActiveForm::end(); ?>