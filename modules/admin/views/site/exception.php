<?php
/* @var $this View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\web\View;
use yiister\gentelella\widgets\Panel;
?>
<?php
Panel::begin([
    'header' => $exception->getFile() . ' : ' . $exception->getLine(),
]);
?>
<div class="alert alert-danger">
    <?= nl2br(Html::encode($exception->getMessage())) ?>
</div>
<p>หากพบปัญหาในการใช้งาน กรุณาติดต่อเจ้าหน้าที่</p>
<?php Panel::end(); ?>