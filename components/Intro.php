<?php

namespace app\components;

use app\models\Configuration;
use Yii;
use yii\base\Model;

class Intro extends Model {

    public $background_file;
    public $background_url;

    public static function getUploadUrl() {
        return Yii::getAlias('@web/uploads/intro');
    }

    public static function getUploadPath() {
        return Yii::getAlias('@webroot/uploads/intro');
    }

    public function init() {
        parent::init();
        $this->background_url = Configuration::getValue('web_intro');
    }

    public function rules() {
        $rules = parent::rules();
        $rules[] = ['background_url', 'safe'];
        return $rules;
    }

    public function getIsActive() {
        return $this->background_url ? true : false;
    }

    public function getBackgroundUrl() {
        if ($this->background_url) {
            return self::getUploadUrl() . DIRECTORY_SEPARATOR . $this->background_url . '.jpg';
        }
        return false;
    }

    public function save() {
        Configuration::setValue('web_intro', $this->background_url);
        return true;
    }

    public function delete() {
        Configuration::setValue('web_intro', '');
        return true;
    }

}
