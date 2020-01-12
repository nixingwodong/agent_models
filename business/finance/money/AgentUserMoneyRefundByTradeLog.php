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
use yii\base\Exception;


/**
 * Class AgentUserMoneyRechargeByTradeLog
 * @package common\models\business\finance\money
 * @property AgentUsersTradeLog $tradeLog
 */
class AgentUserMoneyRefundByTradeLog
{
    use GeneralModelTrait;
    public $tradeLog;
    public $refundMoney;

    public function __construct($tradeLog , $refundMoney)
    {
        $this->tradeLog    = $tradeLog;
        $this->refundMoney = abs($refundMoney);
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function refund()
    {
        $this->check();
        $upUserMoneyModel = (new UpAgentUserMoney($this->tradeLog->user_id , $this->refundMoney));
        if (!$upUserMoneyModel->up()) {
            return  $this->setErrorMsg($upUserMoneyModel->getErrorMsg());
        }
        $addMoneyLog = (new AgentUserMoneyLog());
        if (!$addMoneyLog->addForRefundByTradeLog($this->tradeLog ,$this->refundMoney, $upUserMoneyModel->afterBalance)) {
            return $this->setErrorMsg($addMoneyLog->getOneErrorMsg());
        }
        return true;
    }

    /**
     * @throws Exception
     */
    private function check()
    {
        if (!$this->refundMoney) {
            throw new Exception('错误的退款金额');
        }
    }

}