<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-02
 * Time: 18:18
 */

namespace common\models\business\finance\money;

use common\models\business\trade\AgentUsersTradeLog;
use common\models\traits\GeneralModelTrait;


/**
 * Class AgentUserMoney
 * @package common\models\business\finance\money
 * @property AgentUsersTradeLog $tradeLog
 */
class AgentUserMoneyPayByTradeLog
{
    use GeneralModelTrait;
    public $tradeLog;

    public function __construct($tradeLog)
    {
        $this->tradeLog = $tradeLog;
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function pay()
    {
        $pay = (new UpAgentUserMoney($this->tradeLog->user_id , -abs($this->tradeLog->money)));
        if (!$pay->up()) {
            return $this->setErrorMsg($pay->getErrorMsg());
        }
        $addMoneyLog = new AgentUserMoneyLog();
        if (!$addMoneyLog->addByTrade($this->tradeLog , $pay->afterBalance)) {
            return $this->setErrorMsg($addMoneyLog->getOneErrorMsg());
        }
        return true;
    }

}