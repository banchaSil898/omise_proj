<?php
/* @var $this View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use app\widgets\Breadcrumbs;
use app\widgets\Page;
use yii\helpers\Html;
use yii\web\View;

$this->title = $name;
?>
<?=
Breadcrumbs::widget([
    'items' => [
        [
            'label' => 'พบข้อผิดพลาด',
            'url' => ['#'],
        ],
    ],
]);
?>
<div class="container">
    <?php
    Page::begin([
        'title' => Html::encode($this->title),
    ]);
    ?>
    <div class="site-error">
        <div class="alert alert-danger"><?= Html::encode($message); ?></div>
        <p>The above error occurred while the Web server was processing your request.</p>
        <p>Please contact us if you think this is a server error. Thank you.</p>
    </div>
    <?php Page::end(); ?>
</div>