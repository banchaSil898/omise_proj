<?php

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use codesk\components\Html;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'ติดต่อเรา',
            'url' => ['site/contact'],
        ]
    ],
]);
?>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <?php
            Page::begin([
                'title' => 'แผนกบริการลูกค้า',
            ])
            ?>
            <p><strong><?= Html::awesome('calendar'); ?> เวลาทำการ :</strong> จันทร์-ศุกร์ 09.00-17.00 น.</p>
            <p><strong><?= Html::awesome('calendar'); ?> เวลาทำการ :</strong> Monday-Friday 09.00-17.00</p>
            <div class="alert alert-danger">
                <h4 class="text-bold">สนใจติดต่อเชิญสำนักพิมพ์มติชนออกบูธขายงาน</h4>
                <div style="padding-left:15px;">
                    <p>ส่งข้อมูลได้ที่ บริษัท งานดี จำกัด (ในเครือมติชน)</p>
                    <p><strong><?= Html::awesome('phone'); ?> โทรศัพท์ :</strong> 02-5800021 ต่อ 3358-3360</p>
                </div>
            </div>
            <?php Page::end(); ?>
            <?php
            Page::begin([
                'title' => 'แผนที่',
            ])
            ?>
            <div>
                <?= Html::a(Html::img('@web/images/web/map.jpg', ['class' => 'img-resp']), ['site/map'], ['data-modal' => '1', 'data-modal-size' => 'lg']); ?>
            </div>
            <?php Page::end(); ?>
        </div>
        <div class="col-sm-6">
            <?php
            Page::begin([
                'title' => 'ข้อมูลการติดต่อ',
            ])
            ?>
            <h3 class="text-primary">แผนกขายหนังสือ</h3>
            <div style="padding-left:15px;">
                <p><strong><?= Html::awesome('phone'); ?> โทรศัพท์ :</strong> 0-2589-0020 ต่อ 3350-3351</p>
                <p><strong><?= Html::awesome('print'); ?> โทรสาร :</strong> 0-2591-9012, 0-2591-9014</p>
                <p><strong><?= Html::awesome('envelope'); ?> อีเมล์#1 :</strong> <?= Html::mailto('matichonbook@matichon.co.th'); ?></p>
                <p><strong><?= Html::awesome('envelope'); ?> อีเมล์#2 :</strong> <?= Html::mailto('matichonbook.sales@gmail.com'); ?></p>
                <p><strong><?= Html::awesome('facebook'); ?> Facebook :</strong> <?= Html::a('www.facebook.com/matichonbook', 'https://www.facebook.com/matichonbook', ['target' => '_blank']); ?></p>
            </div>
            <hr/>
            <h3 class="text-primary">แผนกสมาชิกนิตยสาร</h3>
            <div style="padding-left:15px;">
                <p><strong><?= Html::awesome('phone'); ?> โทรศัพท์ :</strong> 0-2589-0020 ต่อ 3352-3353</p>
                <p><strong><?= Html::awesome('print'); ?> โทรสาร :</strong> 0-2591-9012, 0-2591-9014</p>
                <p><strong><?= Html::awesome('envelope'); ?> อีเมล์ :</strong> <?= Html::mailto('mati.members@gmail.com'); ?></p>
                <p><strong><?= Html::awesome('home'); ?> ที่อยู่ :</strong></p> 
                <blockquote>
                    บริษัท งานดี จำกัด<br/>
                    เลขที่ 12 ถนนเทศบาลนฤมาล หมู่บ้านประชานิเวศน์ 1 <br/>
                    แขวงลาดยาว เขตจตุจักร กรุงเทพมหานคร 10900
                </blockquote>
            </div>
            <hr/>
            <h3 class="text-primary">สำนักพิมพ์มติชน</h3>
            <div style="padding-left:15px;">
                <p><strong><?= Html::awesome('phone'); ?> โทรศัพท์ :</strong> 0-2589-0020 ต่อ 1205, 1235</p>
                <p><strong><?= Html::awesome('envelope'); ?> อีเมล์ :</strong> <?= Html::mailto('matichon.book58@gmail.com'); ?></p>
                <p><strong><?= Html::awesome('home'); ?> ที่อยู่ :</strong></p>
                <blockquote>
                    บริษัท มติชน จำกัด (มหาชน)<br/>
                    เลขที่ 12 ถนนเทศบาลนฤมาล หมู่บ้านประชานิเวศน์ 1 <br/>
                    แขวงลาดยาว เขตจตุจักร กรุงเทพมหานคร 10900<br/>
                </blockquote>
                <?= Html::a(Html::awesome('map-marker') . ' Get directions', 'http://maps.google.com/maps?saddr=&daddr=สำนักพิมพ์มติชน', ['target' => '_blank', 'class' => 'btn btn-primary']); ?>
            </div>
            <?php Page::end(); ?>
        </div>
    </div>
</div>
