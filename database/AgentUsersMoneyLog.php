<?php

namespace common\models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_money_log}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $info
 * @property string $money
 * @property string $after_balance
 * @property string $trade_sn
 * @property string $order_sn
 * @property int $order_cate
 * @property int $money_log_type
 * @property int $inout_type
 * @property string $add_time
 */
class AgentUsersMoneyLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_money_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'info', 'money', 'after_balance', 'trade_sn', 'order_sn', 'order_cate', 'money_log_type', 'inout_type'], 'required'],
            [['user_id', 'order_cate', 'money_log_type', 'inout_type'], 'integer'],
            [['money', 'after_balance'], 'number'],
            [['add_time'], 'safe'],
            [['name', 'info', 'trade_sn', 'order_sn'], 'string', 'max' => 255],
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
            'name' => 'Name',
            'info' => 'Info',
            'money' => 'Money',
            'after_balance' => 'After Balance',
            'trade_sn' => 'Trade Sn',
            'order_sn' => 'Order Sn',
            'order_cate' => 'Order Cate',
            'money_log_type' => 'Money Log Type',
            'inout_type' => 'Inout Type',
            'add_time' => 'Add Time',
        ];
    }
}
