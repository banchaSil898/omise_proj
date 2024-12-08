<?php

use codesk\components\Html;
use kartik\form\ActiveForm;
use yii\redactor\widgets\Redactor;
?>
<div class="modal-header">
    <h4 class="modal-title">แสดงตัวอย่าง Header / Footer</h4>
</div>
<div class="modal-body">
    <?= $header->data; ?>
    <div style="padding:10px;">
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer gravida hendrerit magna eget rutrum. Quisque posuere sapien quis ex imperdiet dictum. Sed quis facilisis nisl. Suspendisse tempus tempus tincidunt. Sed porttitor non enim ut posuere. Aliquam sed diam et dolor pretium mollis a a nunc. In ultricies malesuada mauris, at pulvinar sapien vestibulum sed. Integer ultricies orci eget nisl commodo posuere.</p>
        <p>Pellentesque et sem ac tellus laoreet porttitor ac vitae mi. Morbi consectetur porttitor nibh, at eleifend odio pellentesque a. Fusce porttitor in ex in vulputate. Integer hendrerit imperdiet est et mollis. Pellentesque porta, ipsum eu venenatis pellentesque, felis risus eleifend est, sed ullamcorper ligula mauris vel neque. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum sed accumsan nulla. Praesent dolor erat, tincidunt vitae arcu eu, mattis maximus ex. Phasellus sagittis magna nisl, at pellentesque augue accumsan at. Suspendisse potenti. In hac habitasse platea dictumst. Nulla auctor velit non leo hendrerit, vestibulum pellentesque velit venenatis. Mauris in tincidunt orci. Pellentesque ac nunc in ex vulputate fringilla non quis enim. Pellentesque volutpat enim quis ex molestie gravida.</p>
    </div>
    <?= $footer->data; ?>
</div>
<div class="modal-footer">
    <?= Html::a('ปิด', ['#'], ['data-dismiss' => 'modal', 'class' => 'btn btn-default pull-left']); ?>
</div>