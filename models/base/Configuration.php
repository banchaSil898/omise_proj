<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "configuration".
 *
 * @property string $name
 * @property string $data
 * @property string $description
 * @property string $config_group
 * @property string $config_type
 * @property int $order_no
 * @property string $config_options
 */
class Configuration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['data', 'description', 'config_options'], 'string'],
            [['order_no'], 'integer'],
            [['name', 'config_group', 'config_type'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'data' => 'Data',
            'description' => 'Description',
            'config_group' => 'Config Group',
            'config_type' => 'Config Type',
            'order_no' => 'Order No',
            'config_options' => 'Config Options',
        ];
    }
}
