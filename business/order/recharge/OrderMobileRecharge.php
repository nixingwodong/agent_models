<?php

namespace common\models\business\order\recharge;
use common\models\business\trade\AgentUsersTradeLog;

class OrderMobileRecharge extends \common\models\database\OrderMobileRecharge implements OrderConstansInterface
{
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

    public $orderCate = AgentUsersTradeLog::ORDER_CATE_RECHARGE_MOBILE;

    public function rules()
    {
        return [
            ['order_status' , 'in' , 'range' => array_keys(self::ORDER_STATUSES)] ,
            ['recharge_status' , 'in' , 'range' => array_keys(self::RECHARGE_STATUSES)] ,

            [['user_id' , 'trade_sn' , 'employee_id' , 'order_sn' , 'recharge_accounts' , 'recharge_amount' , 'product_id' , 'product_name' , 'isp' , 'province' , 'city' , 'order_status' , 'recharge_status' , 'user_ip' , 'rebate' , 'unit_price' , 'total_price' , 'product_face_value' , 'buy_num' , 'user_retail_price' , 'product_recharge_speed'] , 'required'] ,
            [['user_id' , 'parent_dealer_user_id' , 'employee_id' , 'product_id' , 'order_status' , 'recharge_status' , 'user_ip' , 'product_recharge_speed' , 'add_order_way' ,] , 'integer'] ,
            [['recharge_amount' , 'rebate' , 'unit_price' , 'total_price' , 'product_face_value' , 'buy_num' , 'user_retail_price'] , 'number'] ,
            [['add_time' , 'end_time'] , 'safe'] ,
            [['trade_sn' , 'product_name' , 'user_info'] , 'string' , 'max' => 255] ,
            [['order_sn'] , 'string' , 'max' => 19] ,
            [['recharge_accounts'] , 'string' , 'max' => 11] ,
            [['isp'] , 'string' , 'max' => 2] ,
            [['province'] , 'string' , 'max' => 3] ,
            [['city'] , 'string' , 'max' => 20] ,
            [['order_sn'] , 'unique'] ,
            [['trade_sn'] , 'unique'] ,

        ];
    }
}