<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-02
 * Time: 22:41
 */

namespace common\models\business\finance\money;

use common\models\business\trade\AgentUsersTradeLog;
use common\models\traits\YiiModelTrait;
use common\models\User;

class AgentUserMoneyLog extends \common\models\database\AgentUsersMoneyLog
{
    use YiiModelTrait;
    const MONEY_LOG_TYPE_UNKNOWN           = 0;//未知
    const MONEY_LOG_TYPE_PAY_ORDER         = 1;//订单支付
    const MONEY_LOG_TYPE_CASH_WITHDRAWAL   = 2;//提现
    const MONEY_LOG_TYPE_RECHARGE_BALANCE  = 3;//余额充值
    const MONEY_LOG_TYPE_REFUND            = 4;//退款
    const MONEY_LOG_TYPE_REBATE            = 5;//返佣结算
    const MONEY_LOG_TYPE_TRANSFER          = 6;//转账
    const MONEY_LOG_TYPE_REBATE_WITHDRAWAL = 7;//佣金提取

    const MONEY_LOG_TYPES
        = [
            self::MONEY_LOG_TYPE_UNKNOWN           => '未知' ,
            self::MONEY_LOG_TYPE_PAY_ORDER         => '订单支付' ,
            self::MONEY_LOG_TYPE_CASH_WITHDRAWAL   => '提现' ,
            self::MONEY_LOG_TYPE_RECHARGE_BALANCE  => '余额充值' ,
            self::MONEY_LOG_TYPE_REFUND            => '退款' ,
            self::MONEY_LOG_TYPE_REBATE            => '订单结算' ,
            self::MONEY_LOG_TYPE_TRANSFER          => '转账' ,
            self::MONEY_LOG_TYPE_REBATE_WITHDRAWAL => '佣金提取' ,
        ];

    const INOUT_TYPE_IN  = 1;//收入
    const INOUT_TYPE_OUT = 2;//支出

    const INOUT_TYPES = [self::INOUT_TYPE_IN => '收入' , self::INOUT_TYPE_OUT => '支出'];

    /**
     * @param AgentUsersTradeLog $tradeLog
     * @param $userPayAfterBalance
     * @return bool
     */
    public function addByTrade(AgentUsersTradeLog $tradeLog , $userPayAfterBalance)
    {
        $this->user_id        = $tradeLog->user_id;
        $this->name           = $tradeLog->name;
        $this->add_time       = $tradeLog->add_time;
        $this->info           = $tradeLog->info;
        $this->money          = $tradeLog->money;
        $this->trade_sn       = $tradeLog->trade_sn;
        $this->order_sn       = $tradeLog->order_sn;
        $this->order_cate     = $tradeLog->order_cate;
        $this->money_log_type = $tradeLog->money_log_type;
        $this->after_balance  = $userPayAfterBalance;
        $this->inout_type     = $tradeLog->inout_type;
        return $this->save();
    }

    /**
     * @param AgentUsersTradeLog $tradeLog
     * @param $reundMoney
     * @param $userPayAfterBalance
     * @return bool
     */
    public function addForRefundByTradeLog(AgentUsersTradeLog $tradeLog , $reundMoney , $userPayAfterBalance)
    {
        $this->user_id        = $tradeLog->user_id;
        $this->name           = "$tradeLog->name[退款]";
        $this->add_time       = date('Y-m-d H:i:s');
        $this->info           = "[{$tradeLog->info}][退款]";
        $this->money          = $reundMoney;
        $this->trade_sn       = $tradeLog->trade_sn;
        $this->order_sn       = $tradeLog->order_sn;
        $this->order_cate     = $tradeLog->order_cate;
        $this->money_log_type = self::MONEY_LOG_TYPE_REFUND;
        $this->after_balance  = $userPayAfterBalance;
        $this->inout_type     = $tradeLog->inout_type;

        return $this->save();
    }


}