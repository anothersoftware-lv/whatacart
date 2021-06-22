<?php
namespace common\modules\payment\db\stripe;

use usni\library\db\TableBuilder;
use usni\UsniAdaptor;
/**
 * StripeTransactionTableBuilder class file.
 *
 * @package common\modules\payment\db\stripe
 */
class StripeTransactionTableBuilder extends TableBuilder
{
    /**
     * @inheritdoc
     */
    protected function getTableSchema()
    {
        return [
            'id' => $this->primaryKey(11)->notNull(),
            'order_id' => $this->integer(11)->notNull(),
            'payment_status'  => $this->string(32)->notNull(),
            'received_date'   => $this->date()->notNull(),
            'transaction_id'  => $this->string(32),
            'amount'          => $this->decimal(10,2)->defaultValue(0),
            'debug_data'      => $this->text()
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function getIndexes()
    {
        return [
                    ['idx_payment_status', 'payment_status', false],
                    ['idx_transaction_id', 'transaction_id', false],
                    ['idx_order_id', 'order_id', false]
               ];
    }
    
    /**
     * @inheritdoc
     */
    protected function getForeignKeys()
    {
        $orderTableName         = UsniAdaptor::tablePrefix() . 'order';
        $transactionTableName   = UsniAdaptor::tablePrefix() . 'stripe_transaction';
        return [
                  ['fk_' . $transactionTableName . '_order_id', $transactionTableName, 'order_id', $orderTableName, 'id', 'CASCADE', 'NO ACTION']
               ];
    }
}