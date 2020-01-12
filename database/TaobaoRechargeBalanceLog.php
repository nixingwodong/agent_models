<?php

namespace common\models\database;

use Yii;

/**
 * This is the model class for table "{{%taobao_recharge_balance_log}}".
 *
 * @property int $id
 * @property string $tid
 * @property string $order_sn
 * @property int $user_id
 * @property string $username
 * @property string $real_name
 * @property string $money
 * @property string $buyer_nick
 * @property string $seller_nick
 * @property int $recharge_status
 * @property string $product_name
 * @property int $buy_num
 * @property string $taobao_input_accounts
 * @property string $taobao_input_region
 * @property string $order_details
 * @property string $add_time
 */
class TaobaoRechargeBalanceLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%taobao_recharge_balance_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tid', 'user_id', 'username', 'real_name', 'money', 'seller_nick', 'recharge_status', 'product_name', 'buy_num', 'taobao_input_accounts', 'taobao_input_region'], 'required'],
            [['user_id', 'recharge_status', 'buy_num'], 'integer'],
            [['order_details'], 'string'],
            [['add_time'], 'safe'],
            [['tid'], 'string', 'max' => 30],
            [['order_sn', 'username', 'real_name', 'money', 'buyer_nick', 'seller_nick', 'product_name', 'taobao_input_accounts', 'taobao_input_region'], 'string', 'max' => 255],
            [['tid'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tid' => 'Tid',
            'order_sn' => 'Order Sn',
            'user_id' => 'User ID',
            'username' => 'Username',
            'real_name' => 'Real Name',
            'money' => 'Money',
            'buyer_nick' => 'Buyer Nick',
            'seller_nick' => 'Seller Nick',
            'recharge_status' => 'Recharge Status',
            'product_name' => 'Product Name',
            'buy_num' => 'Buy Num',
            'taobao_input_accounts' => 'Taobao Input Accounts',
            'taobao_input_region' => 'Taobao Input Region',
            'order_details' => 'Order Details',
            'add_time' => 'Add Time',
        ];
    }
}
