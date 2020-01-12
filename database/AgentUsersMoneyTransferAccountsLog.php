<?php

namespace common\models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_money_transfer_accounts_log}}".
 *
 * @property int $id
 * @property string $trade_sn
 * @property string $order_sn
 * @property int $pay_user_id
 * @property int $income_user_id
 * @property string $money
 * @property string $out_user_remark
 * @property string $add_time
 */
class AgentUsersMoneyTransferAccountsLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_money_transfer_accounts_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['trade_sn', 'order_sn', 'pay_user_id', 'income_user_id', 'money', 'add_time'], 'required'],
            [['pay_user_id', 'income_user_id'], 'integer'],
            [['money'], 'number'],
            [['add_time'], 'safe'],
            [['trade_sn', 'order_sn', 'out_user_remark'], 'string', 'max' => 255],
            [['trade_sn'], 'unique'],
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
            'trade_sn' => 'Trade Sn',
            'order_sn' => 'Order Sn',
            'pay_user_id' => 'Pay User ID',
            'income_user_id' => 'Income User ID',
            'money' => 'Money',
            'out_user_remark' => 'Out User Remark',
            'add_time' => 'Add Time',
        ];
    }
}
