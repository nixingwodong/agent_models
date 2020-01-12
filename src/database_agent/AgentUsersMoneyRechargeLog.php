<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_money_recharge_log}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $order_sn
 * @property string $trade_sn
 * @property string $money
 * @property int $payment_method
 * @property string $payment_method_third_order_sn
 * @property int $recharge_status
 * @property string $user_after_balance
 * @property string $info
 * @property string $add_time
 * @property string $end_time
 */
class AgentUsersMoneyRechargeLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_money_recharge_log}}';
    }
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_agent');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'order_sn', 'trade_sn', 'money', 'payment_method', 'payment_method_third_order_sn', 'recharge_status', 'user_after_balance'], 'required'],
            [['user_id', 'payment_method', 'recharge_status'], 'integer'],
            [['money', 'user_after_balance'], 'number'],
            [['add_time', 'end_time'], 'safe'],
            [['order_sn', 'trade_sn', 'payment_method_third_order_sn', 'info'], 'string', 'max' => 255],
            [['payment_method', 'payment_method_third_order_sn'], 'unique', 'targetAttribute' => ['payment_method', 'payment_method_third_order_sn']],
            [['order_sn'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'order_sn' => 'Order Sn',
            'trade_sn' => 'Trade Sn',
            'money' => 'Money',
            'payment_method' => 'Payment Method',
            'payment_method_third_order_sn' => 'Payment Method Third Order Sn',
            'recharge_status' => 'Recharge Status',
            'user_after_balance' => 'User After Balance',
            'info' => 'Info',
            'add_time' => 'Add Time',
            'end_time' => 'End Time',
        ];
    }
}
