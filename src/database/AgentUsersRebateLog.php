<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_rebate_log}}".
 *
 * @property int $id
 * @property int $order_cate
 * @property string $trade_sn
 * @property string $order_sn
 * @property string $info
 * @property int $user_id
 * @property int $order_user_id
 * @property string $rebate
 * @property int $rebate_log_type
 * @property string $after_balance
 * @property string $add_time
 */
class AgentUsersRebateLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_rebate_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['order_cate', 'trade_sn', 'order_sn', 'info', 'user_id', 'order_user_id', 'rebate', 'rebate_log_type', 'after_balance', 'add_time'], 'required'],
            [['order_cate', 'user_id', 'order_user_id', 'rebate_log_type'], 'integer'],
            [['rebate', 'after_balance'], 'number'],
            [['add_time'], 'safe'],
            [['trade_sn', 'order_sn', 'info'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_cate' => 'Order Cate',
            'trade_sn' => 'Trade Sn',
            'order_sn' => 'Order Sn',
            'info' => 'Info',
            'user_id' => 'User ID',
            'order_user_id' => 'Order User ID',
            'rebate' => 'Rebate',
            'rebate_log_type' => 'Rebate Log Type',
            'after_balance' => 'After Balance',
            'add_time' => 'Add Time',
        ];
    }
}
