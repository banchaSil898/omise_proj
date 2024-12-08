<?php

namespace app\modules\admin\components;

use codesk\components\Html as BaseHtml;
use Yii;
use yii\helpers\Url;

class Html extends BaseHtml {

    public static function submitButton($options = array(), $content = 'บันทึกข้อมูล') {
        if (isset($options['class'])) {
            $options['class'] .= ' btn btn-primary';
        } else {
            $options['class'] = 'btn btn-primary';
        }

        if (isset($options['icon'])) {
            $icon = $options['icon'];
            unset($options['icon']);
        } else {
            $icon = 'floppy-save';
        }

        return parent::submitButton(Html::icon($icon) . ' ' . $content, $options);
    }

    public static function goBackUrl($url = null) {
        return $url ? Url::to($url) : Yii::$app->request->referrer;
    }

}
