<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "log_search".
 *
 * @property int $id
 * @property string $keyword
 * @property string $created_at
 * @property string $updated_at
 * @property int $result_count
 * @property int $result_time
 * @property int $member_id
 */
class LogSearch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log_search';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['keyword'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['result_count', 'result_time', 'member_id'], 'integer'],
            [['keyword'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'keyword' => 'Keyword',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'result_count' => 'Result Count',
            'result_time' => 'Result Time',
            'member_id' => 'Member ID',
        ];
    }
}
