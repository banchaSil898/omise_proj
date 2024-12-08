<?php
/* @var $this View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\web\View;
use yiister\gentelella\widgets\Panel;

$this->title = $name;
?>
<?php
Panel::begin([
    'header' => $this->title,
]);
?>
<div class="alert alert-danger">
    <?= nl2br(Html::encode($message)) ?>
</div>
<p>หากพบปัญหาในการใช้งาน กรุณาติดต่อเจ้าหน้าที่</p>
<p><?= Html::a('[กลับไปยังเว็บไซต์]', ['site/index']); ?></p>
<?php Panel::end(); ?>