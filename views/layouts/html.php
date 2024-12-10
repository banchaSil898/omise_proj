<?php

use app\assets\AppAsset;
use app\models\Configuration;
use yii\bootstrap\Html;
use yii\web\View;

/* @var $this View */
$bundle = AppAsset::register($this);
$this->registerCssFile($bundle->baseUrl . '/css/theme/' . Configuration::getValue('web_theme', 'green') . '.css', [
    'depends' => [
        'app\assets\AppAsset',
    ],
]);

$this->registerJs("
    (function(w,d,s,l,i){
        w[l]=w[l]||[];
        w[l].push({
            'gtm.start': new Date().getTime(),event:'gtm.js'
        });
        var
            f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';
            j.async=true;
            j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;
            f.parentNode.insertBefore(j,f);
    }) (window,document,'script','dataLayer','GTM-MXPC92S');", View::POS_HEAD);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- Meta -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width">
        <meta name="description" content="">
        <link rel="apple-touch-icon" sizes="57x57" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?= $bundle->baseUrl; ?>/images/favicon/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="<?= $bundle->baseUrl; ?>/images/favicon/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="<?= $bundle->baseUrl; ?>/images/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="<?= $bundle->baseUrl; ?>/images/favicon/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="<?= $bundle->baseUrl; ?>/images/favicon/favicon-16x16.png">
        <link rel="manifest" href="<?= $bundle->baseUrl; ?>/images/favicon/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?= $bundle->baseUrl; ?>/images/favicon/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->context->title); ?> - MatichonBook.com</title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MXPC92S" height="0" width="0" style="display:none;visibility:hidden;">

        </iframe>
        <?= $content; ?>
        <?php $this->endBody() ?>
        <script>
            var doc = document.documentElement;
            doc.setAttribute('data-useragent', navigator.userAgent);
        </script>
    </body>
</html>
<?php $this->endPage() ?>