<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class HighchartsAsset extends AssetBundle {

    public $sourcePath = '@module/web';
    public $js = [
        'js/vendor/highcharts-6.1.1/highcharts.js',
    ];
    public $css = [
        'js/vendor/highcharts-6.1.1/css/highcharts.css',
    ];
    public $depends = [
        'app\modules\admin\assets\AppAsset',
    ];
    public $publishOptions = [
        'forceCopy' => YII_ENV_DEV,
    ];

}
