<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "payment_log".
 *
 * @property int $id
 * @property string $pmgwresp
 * @property string $created_at
 * @property string $updated_at
 * @property string $ip
 */
class PaymentLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            [['pmgwresp'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pmgwresp' => 'Pmgwresp',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'ip' => 'Ip',
        ];
    }
}
