<?php

use yii\bootstrap\Html;
?>
<div id="pdpa-box" style="display:none;">
    <div class="container-fluid">
        <div class="col-md-8">
            <h4>เว็บไซต์นี้ใช้คุกกี้</h4>
            <p>เพื่อสร้างประสบการณ์นำเสนอคอนเทนต์ที่ดีให้กับท่าน รวมถึงเพื่อจัดการข้อมูลส่วนบุคคลเพื่อให้ท่านได้รับประสบการณ์ที่ดีบนบริการของเว็บไซต์เรา หากท่านใช้บริการเว็บไซต์นี้ต่อไปโดยไม่มีการปรับตั้งค่าใดๆ นั่นเป็นการแสดงว่าท่านอนุญาตยินยอมที่จะรับคุกกี้บนเว็บไซต์และนโยบายสิทธิส่วนบุคคลของเรา</p>
        </div>
        <div class="col-md-4 text-center pdpa-footer" style="padding:3rem;">
            <?= Html::button('ยอมรับ', ['id' => 'btn-accept-pdpa', 'class' => 'btn btn-success']); ?>
            <?= Html::a('เรียนรู้เพิ่มเติม', ['site/privacy-policy'], ['class' => 'btn btn-default']); ?>
        </div>
    </div>
</div>
<?php
$this->registerJs(<<<JS
        
        function initPdpa() {
            if(!isPdpaAccepted()) {
                $("#pdpa-box").show();
            }
        }
        
        $('#btn-accept-pdpa').on('click',function(){
            if(!isPdpaAccepted()) {
              setCookie('pdpa_accept',1,365);
            }
            $("#pdpa-box").hide();
        });
        
        initPdpa();
JS
);
?>
