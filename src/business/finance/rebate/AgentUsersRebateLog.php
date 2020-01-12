<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-02
 * Time: 22:41
 */

namespace agent_models\business\finance\rebate;

use agent_models\business\order\recharge\OrderRechargeAll;

class AgentUsersRebateLog extends \agent_models\database\AgentUsersRebateLog
{

    const REBATE_LOG_TYPE_UNKNOWN           = 0;//未知
    const REBATE_LOG_TYPE_ORDER_REBATE      = 1;//订单佣金结算
    const REBATE_LOG_TYPE_SPREAD_REBATE     = 2;//推广佣金
    const REBATE_LOG_TYPE_SPREAD_REFUND     = 3;//推广退款
    const REBATE_LOG_TYPE_ORDER_REFUND      = 4;//订单退款
    const REBATE_LOG_TYPE_REBATE_WITHDRAWAL = 5;//佣金提取

    const REBATE_LOG_TYPES
        = [
            self::REBATE_LOG_TYPE_UNKNOWN       => '未知' ,
            self::REBATE_LOG_TYPE_ORDER_REBATE  => '订单佣金结算' ,
            self::REBATE_LOG_TYPE_SPREAD_REBATE => '推广佣金' ,
            self::REBATE_LOG_TYPE_SPREAD_REFUND => '推广退款' ,
            self::REBATE_LOG_TYPE_ORDER_REFUND  => '订单退款' ,

            self::REBATE_LOG_TYPE_REBATE_WITHDRAWAL => '提取佣金' ,
        ];

    public function addRebateLogByOrder(OrderRechargeAll $order , $rebate_log_type , $info , $after_balance)
    {
        $this->order_user_id   = $order->user_id;
        $this->user_id         = $order->parent_dealer_user_id;
        $this->info            = $info;
        $this->add_time        = $order->add_time;
        $this->rebate          = abs($order->rebate);
        $this->trade_sn        = $order->trade_sn;
        $this->order_sn        = $order->order_sn;
        $this->order_cate      = $order->order_cate;
        $this->rebate_log_type = $rebate_log_type;
        $this->after_balance   = $after_balance;
        return $this->save();
    }


    public function addRebateLogForRefund(OrderRechargeAll $order , $rebate_log_type , $info , $reund_rebate , $after_balance)
    {
        $this->order_user_id   = $order->user_id;
        $this->user_id         = $order->parent_dealer_user_id;
        $this->add_time        = $order->add_time;
        $this->info            = $info;
        $this->rebate          = -abs($reund_rebate);
        $this->trade_sn        = $order->trade_sn;
        $this->order_sn        = $order->order_sn;
        $this->order_cate      = $order->order_cate;
        $this->rebate_log_type = $rebate_log_type;
        $this->after_balance   = $after_balance;
        return $this->save();
    }

}