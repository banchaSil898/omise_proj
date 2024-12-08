<?php

namespace app\models;

use app\models\base\Configuration as BaseConfiguration;
use yii\helpers\ArrayHelper;

class Configuration extends BaseConfiguration {

    public static function setValue($name, $value) {
        $config = Configuration::findOne([
                    'name' => $name,
        ]);
        if (isset($config)) {
            $config->data = $value;
            return $config->save();
        }
        return false;
    }

    public static function getValue($name, $default = null) {
        if (is_array($name)) {
            $ret = [];
            $configs = Configuration::find()->where(['name' => $name])->indexBy('name')->orderBy(['order_no' => SORT_ASC])->all();
            foreach ($configs as $config) {
                $ret[$config->name] = $config->data;
            }
            return $ret;
        } else {
            $config = Configuration::findOne([
                        'name' => $name,
            ]);
            if (isset($config)) {
                return $config->data;
            }
            return $default;
        }
    }

    public static function getValuesByType($type) {
        return Configuration::find()->where(['config_type' => $type])->indexBy('name')->orderBy(['order_no' => SORT_ASC])->all();
    }

    public static function getValuesByGroup($type) {
        return ArrayHelper::map(Configuration::find()->where(['config_group' => $type])->orderBy(['order_no' => SORT_ASC])->all(), 'name', 'data');
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), [
            'description' => 'ตัวแปร',
            'data' => 'กำหนดค่า',
        ]);
    }

}
