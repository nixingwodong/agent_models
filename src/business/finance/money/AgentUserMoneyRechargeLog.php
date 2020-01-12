<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-03-04
 * Time: 18:38
 */

namespace agent_models\business\finance\money;

use agent_models\business\trade\AgentUsersTradeLog;
use agent_models\traits\YiiModelTrait;

class AgentUserMoneyRechargeLog extends \agent_models\database\AgentUsersMoneyRechargeLog
{
    use YiiModelTrait;
    const RECHARGE_STATUS_UNKNOWN       = 0;//未知
    const RECHARGE_STATUS_WAIT_PAY      = 1;//等待支付
    const RECHARGE_STATUS_WAIT_RECHARGE = 2;//等待充值
    const RECHARGE_STATUS_RECHARGE      = 3;//充值中
    const RECHARGE_STATUS_SUCCESS       = 4;//充值成功

    const RECHARGE_STATUSES
        = [
            self::RECHARGE_STATUS_UNKNOWN       => '未知' ,
            self::RECHARGE_STATUS_WAIT_PAY      => '等待支付' ,
            self::RECHARGE_STATUS_WAIT_RECHARGE => '等待充值' ,
            self::RECHARGE_STATUS_RECHARGE      => '充值中' ,
            self::RECHARGE_STATUS_SUCCESS       => '充值成功' ,
        ];


    const PAYMENT_METHOD_TAOBAO_SHOP=AgentUsersTradeLog::PAYMENT_METHOD_TAOBAO_SHOP;
    const PAYMENT_METHODS=[
        self::PAYMENT_METHOD_TAOBAO_SHOP=>'淘宝店铺',
    ];

    public static function getPaymentMethod()
    {
        return AgentUsersTradeLog::getPaymentMethod();
    }


    public static function buildAnOrderSn($prefix = '')
    {
        return $prefix . ltrim(date('YmdHis') , '20') . str_pad(mt_rand(1 , 99999) , 5 , '0' , STR_PAD_LEFT);
    }


}