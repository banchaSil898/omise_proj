<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {

    public $sourcePath = '@module/web';
    public $css = [
        'css/style.css',
        'css/mobile.css',
        'js/vendor/jquery-confirm-v3.1.1/dist/jquery-confirm.min.css',
        'https://vjs.zencdn.net/7.1.0/video-js.css',
    ];
    public $js = [
        'js/script.js',
        'js/vendor/jquery-confirm-v3.1.1/dist/jquery-confirm.min.js',
        'https://vjs.zencdn.net/7.1.0/video.js',
        'js/vendor/jquery.form.min.js',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'codesk\assets\WebFontAsset',
    ];

}
