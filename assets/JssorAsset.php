<?php

namespace app\assets;

use yii\web\AssetBundle;

class JssorAsset extends AssetBundle {

    public $basePath = '@webroot/js/vendor/jssor-carousel';
    public $baseUrl = '@web/js/vendor/jssor-carousel';
    public $js = [
        'js/jssor.slider.min.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];

}
