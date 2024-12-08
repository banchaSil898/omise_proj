<?php

namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class JqueryTreeAsset extends AssetBundle {

    public $sourcePath = '@module/web';
    public $css = [
        'js/vendor/jquery-tree/src/css/jquery.tree.css',
    ];
    public $js = [
        'js/vendor/jquery-tree/src/js/jquery.tree.js',
    ];
    public $publishOptions = [
        'forceCopy' => true,
    ];
    public $depends = [
        'yii\jui\JuiAsset',
    ];

}
