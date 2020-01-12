<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%order_tencent_recharge}}".
 *
 * @property int $id
 * @property int $user_id
 * @property int $parent_dealer_user_id
 * @property int $employee_id
 * @property string $trade_sn
 * @property string $order_sn
 * @property string $recharge_accounts
 * @property string $recharge_amount
 * @property int $product_id
 * @property int $product_tencent_cate
 * @property string $product_name
 * @property int $order_status
 * @property int $recharge_status
 * @property int $user_ip
 * @property string $unit_price
 * @property string $total_price
 * @property string $rebate
 * @property string $product_face_value
 * @property string $buy_num
 * @property string $user_retail_price
 * @property string $user_info
 * @property int $add_order_way
 * @property string $add_time
 * @property string $end_time
 */
class OrderTencentRecharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_tencent_recharge}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'trade_sn', 'order_sn', 'recharge_accounts', 'recharge_amount', 'product_id', 'product_tencent_cate', 'product_name', 'order_status', 'recharge_status', 'user_ip', 'unit_price', 'total_price', 'rebate', 'product_face_value', 'buy_num', 'user_retail_price'], 'required'],
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'product_id', 'product_tencent_cate', 'order_status', 'recharge_status', 'user_ip', 'add_order_way'], 'integer'],
            [['recharge_amount', 'unit_price', 'total_price', 'rebate', 'product_face_value', 'buy_num', 'user_retail_price'], 'number'],
            [['add_time', 'end_time'], 'safe'],
            [['trade_sn', 'product_name', 'user_info'], 'string', 'max' => 255],
            [['order_sn'], 'string', 'max' => 19],
            [['recharge_accounts'], 'string', 'max' => 11],
            [['order_sn'], 'unique'],
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
            'user_id' => 'User ID',
            'parent_dealer_user_id' => 'Parent Dealer User ID',
            'employee_id' => 'Employee ID',
            'trade_sn' => 'Trade Sn',
            'order_sn' => 'Order Sn',
            'recharge_accounts' => 'Recharge Accounts',
            'recharge_amount' => 'Recharge Amount',
            'product_id' => 'Product ID',
            'product_tencent_cate' => 'Product Tencent Cate',
            'product_name' => 'Product Name',
            'order_status' => 'Order Status',
            'recharge_status' => 'Recharge Status',
            'user_ip' => 'User Ip',
            'unit_price' => 'Unit Price',
            'total_price' => 'Total Price',
            'rebate' => 'Rebate',
            'product_face_value' => 'Product Face Value',
            'buy_num' => 'Buy Num',
            'user_retail_price' => 'User Retail Price',
            'user_info' => 'User Info',
            'add_order_way' => 'Add Order Way',
            'add_time' => 'Add Time',
            'end_time' => 'End Time',
        ];
    }
}
