<?php
namespace common\models\business\order\recharge;

use common\models\business\trade\AgentUsersTradeLog;

class OrderTencentRecharge extends \common\models\database\OrderTencentRecharge implements OrderConstansInterface{
    use OrderRechargeModelTrait;

    const ADD_ORDER_WAY_UNKNOWN = 0;//未知
    const ADD_ORDER_WAY_SINGLE  = 1;//单笔
    const ADD_ORDER_WAY_BATCH   = 2;//批量

    const ADD_ORDER_WAYS
        = [
            self::ADD_ORDER_WAY_UNKNOWN => '未知' ,
            self::ADD_ORDER_WAY_SINGLE  => '单笔充值' ,
            self::ADD_ORDER_WAY_BATCH   => '批量充值' ,
        ];
    public $orderCate = AgentUsersTradeLog::ORDER_CATE_RECHARGE_TENCENT;

    public function rules()
    {
        return [
            [['user_id', 'parent_dealer_user_id',  'trade_sn', 'order_sn', 'recharge_accounts', 'recharge_amount', 'product_id', 'product_tencent_cate', 'product_name', 'order_status', 'recharge_status', 'user_ip', 'unit_price', 'total_price', 'rebate', 'product_face_value', 'buy_num', 'user_retail_price'], 'required'],
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
}