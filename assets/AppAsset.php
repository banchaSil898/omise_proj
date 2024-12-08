<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        //'css/bootstrap.min.css',
        'css/main.css',
        //'css/green.css',
        'css/bootstrap-select.min.css',
        'css/font-awesome.min.css',
        'css/owl.carousel.css',
        'css/owl.transitions.css',
        'css/animate.min.css',
        'css/jquery-ui.min.css',
        'https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500italic,500,700,700italic,900,900italic',
        'css/elegant-fonts.css',
        'css/font.css',
        'css/style.css',
        'css/slick.css',
        'css/mobile.css',
        'js/vendor/jquery-confirm-v3.1.1/dist/jquery-confirm.min.css',
        'https://vjs.zencdn.net/7.1.0/video-js.css',
        'css/pdpa.css?ver=1.0',
    ];
    public $js = [
        'js/bootstrap-hover-dropdown.min.js',
        'js/echo.min.js',
        'js/jquery.easing.min.js',
        'js/owl.carousel.min.js',
        'js/wow.min.js',
        'js/bootstrap-select.min.js',
        'js/jquery-ui.min.js',
        'js/scripts.js',
        'js/core.js',
        'js/vendor/jquery-confirm-v3.1.1/dist/jquery-confirm.min.js',
        'https://vjs.zencdn.net/7.1.0/video.js',
        'js/pdpa.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'codesk\assets\WebFontAsset',
    ];

}
