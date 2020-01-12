<?php
namespace agent_models\business\order\recharge;
interface OrderConstansInterface{
    const ORDER_STATUS_UNKNOWN  = 0;//未知
    const ORDER_STATUS_RECHARGE = 1;//充值中
    const ORDER_STATUS_SUCCESS  = 2;//充值成功
    const ORDER_STATUS_FAILURE  = 3;//充值失败
    const ORDER_STATUS_CANCEL   = 4;//已取消

    const RECHARGE_STATUS_UNKNOWN          = 0;
    const RECHARGE_STATUS_WAIT_RECHARGE    = 1;
    const RECHARGE_STATUS_VERIFIED         = 2;
    const RECHARGE_STATUS_SUB_DUBIOUS      = 3;
    const RECHARGE_STATUS_SUB_FAILURE      = 4;
    const RECHARGE_STATUS_RECHARGE         = 5;
    const RECHARGE_STATUS_SUCCESS          = 6;
    const RECHARGE_STATUS_FAILURE          = 7;
    const RECHARGE_STATUS_RECHARGE_DUBIOUS = 8;
    const RECHARGE_STATUS_REFUND           = 9;
    const RECHARGE_STATUS_WAIT_MANUAL      = 10;


    const ORDER_STATUSES
        = [
            self::ORDER_STATUS_UNKNOWN  => '未知' ,
            self::ORDER_STATUS_RECHARGE => '充值中' ,
            self::ORDER_STATUS_SUCCESS  => '充值成功' ,
            self::ORDER_STATUS_FAILURE  => '充值失败' ,
            self::ORDER_STATUS_CANCEL   => '已取消' ,
        ];
    const RECHARGE_STATUSES
        = [
            self::RECHARGE_STATUS_UNKNOWN          => '未知' ,
            self::RECHARGE_STATUS_WAIT_RECHARGE    => '等待充值' ,
            self::RECHARGE_STATUS_VERIFIED         => '已验单' ,
            self::RECHARGE_STATUS_SUB_DUBIOUS      => '提交可疑' ,
            self::RECHARGE_STATUS_SUB_FAILURE      => '提交失败' ,
            self::RECHARGE_STATUS_RECHARGE         => '充值中' ,
            self::RECHARGE_STATUS_SUCCESS          => '充值成功' ,
            self::RECHARGE_STATUS_FAILURE          => '充值失败' ,
            self::RECHARGE_STATUS_RECHARGE_DUBIOUS => '充值可疑' ,
            self::RECHARGE_STATUS_REFUND           => '已退款' ,
            self::RECHARGE_STATUS_WAIT_MANUAL      => '待人工处理' ,
        ];

}