<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "log_search_keyword".
 *
 * @property string $keyword
 * @property int $result_count
 * @property int $result_time
 */
class LogSearchKeyword extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_search_keyword';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keyword'], 'required'],
            [['result_count', 'result_time'], 'integer'],
            [['keyword'], 'string', 'max' => 120],
            [['keyword'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'keyword' => 'Keyword',
            'result_count' => 'Result Count',
            'result_time' => 'Result Time',
        ];
    }
}
