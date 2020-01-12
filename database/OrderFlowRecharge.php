<?php

namespace common\models\database;

use Yii;

/**
 * This is the model class for table "{{%order_flow_recharge}}".
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
 * @property string $product_name
 * @property string $isp
 * @property string $province
 * @property string $city
 * @property int $order_status
 * @property int $recharge_status
 * @property int $user_ip
 * @property string $rebate
 * @property string $unit_price
 * @property string $total_price
 * @property string $product_face_value
 * @property string $buy_num
 * @property string $user_retail_price
 * @property string $user_info
 * @property int $product_recharge_speed
 * @property int $add_order_way
 * @property string $add_time
 * @property string $end_time
 */
class OrderFlowRecharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_flow_recharge}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'trade_sn', 'order_sn', 'recharge_accounts', 'recharge_amount', 'product_id', 'product_name', 'isp', 'province', 'city', 'order_status', 'recharge_status', 'user_ip', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price', 'product_recharge_speed'], 'required'],
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'product_id', 'order_status', 'recharge_status', 'user_ip', 'product_recharge_speed', 'add_order_way'], 'integer'],
            [['recharge_amount', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price'], 'number'],
            [['add_time', 'end_time'], 'safe'],
            [['trade_sn', 'product_name', 'user_info'], 'string', 'max' => 255],
            [['order_sn'], 'string', 'max' => 19],
            [['recharge_accounts'], 'string', 'max' => 11],
            [['isp'], 'string', 'max' => 2],
            [['province'], 'string', 'max' => 3],
            [['city'], 'string', 'max' => 20],
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
            'product_name' => 'Product Name',
            'isp' => 'Isp',
            'province' => 'Province',
            'city' => 'City',
            'order_status' => 'Order Status',
            'recharge_status' => 'Recharge Status',
            'user_ip' => 'User Ip',
            'rebate' => 'Rebate',
            'unit_price' => 'Unit Price',
            'total_price' => 'Total Price',
            'product_face_value' => 'Product Face Value',
            'buy_num' => 'Buy Num',
            'user_retail_price' => 'User Retail Price',
            'user_info' => 'User Info',
            'product_recharge_speed' => 'Product Recharge Speed',
            'add_order_way' => 'Add Order Way',
            'add_time' => 'Add Time',
            'end_time' => 'End Time',
        ];
    }
}
