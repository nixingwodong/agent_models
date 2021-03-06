<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-02
 * Time: 18:18
 */

namespace agent_models\business\finance\money;

use agent_models\business\trade\AgentUsersTradeLog;
use agent_models\traits\GeneralModelTrait;


/**
 * Class AgentUserMoneyRechargeByTradeLog
 * @package agent_models\business\finance\money
 * @property AgentUsersTradeLog $tradeLog
 */
class AgentUserMoneyRechargeByTradeLog
{
    use GeneralModelTrait;
    public $tradeLog;

    public function __construct($tradeLog)
    {
        $this->tradeLog = $tradeLog;
    }

    /**
     * @return bool|false
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function recharge()
    {
        $recharge = new UpAgentUserMoney($this->tradeLog->user_id , abs($this->tradeLog->money));
        if (!$recharge->up()) {
            return $this->setErrorMsg($recharge->getErrorMsg());
        }
        $addMoneyLog = (new AgentUserMoneyLog());
        if (!$addMoneyLog->addByTrade($this->tradeLog , $recharge->afterBalance)) {
            return $this->setErrorMsg($addMoneyLog->getOneErrorMsg());
        }
        return true;
    }
}