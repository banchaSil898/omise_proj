<?php

namespace app\models\base;

use Yii;
use app\models\Purchase;
/**
 * This is the model class for table "omise_payments".
 *
 * @property int $id
 * @property int $order_id
 * @property string $charge_id
 * @property string $amount
 * @property string $net
 * @property string $fee
 * @property string $fee_vat
 * @property string $currency
 * @property string $status
 * @property string $payment_method
 * @property string $transaction_date
 * @property string $created_at
 * 
//  * @property Purchase $purchase
 */
class OmisePayments extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%omise_payments}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_id', 'charge_id'], 'required'],
            [['order_id'], 'integer'],
            [['amount', 'net', 'fee', 'fee_vat'], 'number'],
            [['transaction_date', 'created_at'], 'safe'],
            [['charge_id'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 10],
            [['status', 'payment_method'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'charge_id' => 'Charge ID',
            'amount' => 'Amount',
            'net' => 'Net',
            'fee' => 'Fee',
            'fee_vat' => 'Fee Vat',
            'currency' => 'Currency',
            'status' => 'Status',
            'payment_method' => 'Payment Method',
            'transaction_date' => 'Transaction Date',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPurchase(){
        return $this->hasOne(Purchase::class, ['id' => 'order_id']);
    }
}