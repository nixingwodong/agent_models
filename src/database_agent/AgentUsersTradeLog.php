<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_trade_log}}".
 *
 * @property int $id
 * @property string $trade_sn
 * @property string $order_sn
 * @property int $user_id
 * @property string $money
 * @property int $order_cate
 * @property string $name
 * @property int $trade_status
 * @property string $info
 * @property int $payment_method
 * @property int $inout_type
 * @property int $trade_log_type
 * @property int $money_log_type
 * @property string $add_time
 * @property string $pay_time
 * @property string $end_time
 */
class AgentUsersTradeLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_trade_log}}';
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
            [['trade_sn', 'order_sn', 'user_id', 'money', 'order_cate', 'name', 'trade_status', 'info', 'payment_method', 'inout_type', 'trade_log_type', 'money_log_type'], 'required'],
            [['user_id', 'order_cate', 'trade_status', 'payment_method', 'inout_type', 'trade_log_type', 'money_log_type'], 'integer'],
            [['money'], 'number'],
            [['add_time', 'pay_time', 'end_time'], 'safe'],
            [['trade_sn', 'order_sn', 'name', 'info'], 'string', 'max' => 255],
            [['trade_sn'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trade_sn' => 'Trade Sn',
            'order_sn' => 'Order Sn',
            'user_id' => 'User ID',
            'money' => 'Money',
            'order_cate' => 'Order Cate',
            'name' => 'Name',
            'trade_status' => 'Trade Status',
            'info' => 'Info',
            'payment_method' => 'Payment Method',
            'inout_type' => 'Inout Type',
            'trade_log_type' => 'Trade Log Type',
            'money_log_type' => 'Money Log Type',
            'add_time' => 'Add Time',
            'pay_time' => 'Pay Time',
            'end_time' => 'End Time',
        ];
    }
}
