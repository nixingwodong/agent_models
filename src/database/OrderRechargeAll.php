<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%order_recharge_all}}".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $parent_dealer_user_id 上级用户id
 * @property int $employee_id
 * @property string $trade_sn 交易流水号
 * @property string $order_sn 订单编号
 * @property int $order_cate 订单分类
 * @property string $recharge_accounts 充值账号
 * @property string $recharge_amount 充值金额
 * @property int $product_id 商品id
 * @property string $product_name 商品名称
 * @property int $order_status 订单状态
 * @property int $recharge_status 充值状态
 * @property int $user_ip 下单时的用户IP
 * @property string $rebate 返利给经销商的金额
 * @property string $unit_price 商品单价
 * @property string $total_price 总价
 * @property string $product_face_value 商品面值
 * @property string $buy_num 购买数量
 * @property string $user_retail_price 用户卖给客户的零售价
 * @property string $add_time 下单时间
 * @property string $end_time 完成时间
 */
class OrderRechargeAll extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%order_recharge_all}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'trade_sn', 'order_sn', 'order_cate', 'recharge_accounts', 'recharge_amount', 'product_id', 'product_name', 'order_status', 'recharge_status', 'user_ip', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price'], 'required'],
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'order_cate', 'product_id', 'order_status', 'recharge_status', 'user_ip'], 'integer'],
            [['recharge_amount', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price'], 'number'],
            [['add_time', 'end_time'], 'safe'],
            [['trade_sn', 'product_name'], 'string', 'max' => 255],
            [['order_sn'], 'string', 'max' => 19],
            [['recharge_accounts'], 'string', 'max' => 11],
            [['order_sn', 'order_cate'], 'unique', 'targetAttribute' => ['order_sn', 'order_cate']],
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
            'order_cate' => 'Order Cate',
            'recharge_accounts' => 'Recharge Accounts',
            'recharge_amount' => 'Recharge Amount',
            'product_id' => 'Product ID',
            'product_name' => 'Product Name',
            'order_status' => 'Order Status',
            'recharge_status' => 'Recharge Status',
            'user_ip' => 'User Ip',
            'rebate' => 'Rebate',
            'unit_price' => 'Unit Price',
            'total_price' => 'Total Price',
            'product_face_value' => 'Product Face Value',
            'buy_num' => 'Buy Num',
            'user_retail_price' => 'User Retail Price',
            'add_time' => 'Add Time',
            'end_time' => 'End Time',
        ];
    }
}
