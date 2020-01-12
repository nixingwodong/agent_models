<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%order_mobile_recharge}}".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $parent_dealer_user_id 上级经销商用户id
 * @property int $employee_id
 * @property string $trade_sn 交易流水号
 * @property string $order_sn 业务订单号
 * @property string $recharge_accounts 充值账号
 * @property string $recharge_amount 充值金额
 * @property int $product_id 商品id
 * @property string $product_name 商品名称
 * @property string $isp 号码所属运营商
 * @property string $province 号码所属省份
 * @property string $city 号码所属城市
 * @property int $order_status 订单状态
 * @property int $recharge_status 充值状态
 * @property int $user_ip 用户下单ip
 * @property string $rebate 返利给经销商的金额
 * @property string $unit_price 商品单价
 * @property string $total_price 订单总价
 * @property string $product_face_value 商品面值
 * @property string $buy_num 购买的商品数量
 * @property string $user_retail_price 用户卖给客户的零售价
 * @property string $user_info 用户的备注
 * @property int $product_recharge_speed 商品的充值速度
 * @property int $add_order_way 下单方式
 * @property string $add_time 下单时间
 * @property string $end_time 完成时间
 */
class OrderMobileRecharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_mobile_recharge}}';
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
