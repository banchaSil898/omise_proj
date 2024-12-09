<?php

use yii\db\Migration;

/**
 * Class m241029_100818_create_omise_payments_log
 */
class m241029_100818_create_omise_payments_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%omise_payments}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull()->unsigned(),
            'charge_id' => $this->string(255)->notNull(),
            'amount' => $this->decimal(),
            'net' => $this->decimal(),
            'fee' => $this->decimal(),
            'fee_vat' => $this->decimal(),
            'currency' => $this->string(10)->notNull()->defaultValue('THB'),
            'status' => $this->string(50)->defaultValue('pending'),
            'payment_method' => $this->string(50),
            'transaction_date' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        // เพิ่ม Foreign Key ไปยังตาราง orders
        $this->addForeignKey(
            'fk-payments-order_id',
            '{{%omise_payments}}',
            'order_id',
            '{{%purchase}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // ลบ Foreign Key ก่อน
        // $this->dropForeignKey('fk-payments-order_id', '{{%omise_payments}}');

        // ลบตาราง payments
        $this->dropTable('{{%omise_payments}}');
        
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241029_100818_create_omise_payments_log cannot be reverted.\n";

        return false;
    }
    */
}
