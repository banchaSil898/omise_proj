<?php 

use app\widgets\Breadcrumbs;

?>
<div class="slider">
    <div id="hero">
        <div id="owl-main" class="owl-carousel owl-theme">
            <?php foreach ($slides as $slide): ?>
                <?=
                $this->render('/widgets/slide-item', [
                    'model' => $slide,
                ]);
                ?>
            <?php endforeach; ?>
        </div><!-- /#owl-main -->
    </div>
</div>
<?=
Breadcrumbs::widget([
    'showHome' => false,
]);
?>