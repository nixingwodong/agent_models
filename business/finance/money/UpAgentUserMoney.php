<?php

namespace common\models\business\finance\money;

use common\models\database\AgentUsers;
use common\models\traits\GeneralModelTrait;
use yii\db\Exception;

class UpAgentUserMoney
{
    use GeneralModelTrait;
    const ERROR_CODE_USER_NULL               = 1;
    const ERROR_CODE_USER_INSUFFICIENT_FUNDS = 2;
    const ERROR_CODE_DEDUCT_FAILURE          = 3;
    const ERROR_CODE_DEPOSIT_FAILURE         = 4;

    const ERROR_CODES
        = [
            self::ERROR_CODE_USER_NULL               => '操作失败，用户不存在' ,
            self::ERROR_CODE_USER_INSUFFICIENT_FUNDS => '扣款失败，用户余额不足' ,
            self::ERROR_CODE_DEDUCT_FAILURE          => '扣款失败，系统异常' ,
            self::ERROR_CODE_DEPOSIT_FAILURE         => '存款失败，系统异常' ,
        ];
    public $errorCode;
    public $errorMsg;

    public $userId;
    public $oprationMoney;
    public $beforeBalance;
    public $afterBalance;
    public $platformTotalAfterBalance;


    public function __construct($userId , $oprationMoney)
    {
        $this->userId        = (int)$userId;
        $this->oprationMoney = $oprationMoney;
    }

    /**
     * @return bool
     * @throws Exception
     * @throws \yii\base\Exception
     */
    public function up()
    {
        $this->check();
        $userId        = $this->userId;
        $oprationMoney = $this->oprationMoney;

        $tableName = AgentUsers::tableName();
        $t         = \Yii::$app->db->beginTransaction();
        $user      = \Yii::$app->db->createCommand("SELECT money FROM {$tableName} WHERE id={$userId} for update")->queryOne();
        if (!$user) {
            return $this->setErrorByErrorCode(self::ERROR_CODE_USER_NULL);//用户不存在
        }
        $userBalance = $user['money'];
        //如果money小于0则扣款
        if ($oprationMoney < 0) {
            $absMoney = abs($oprationMoney);
            if ($userBalance < $absMoney) {//$oprationMoney可能为负数,要转换成正数才能比较大小
                $t->rollBack();
                return $this->setErrorByErrorCode(self::ERROR_CODE_USER_INSUFFICIENT_FUNDS , "余额{$userBalance}元，不足以扣款{$absMoney}元");//余额不足
            }
            $up = AgentUsers::updateAllCounters(['money' => $oprationMoney] , ['and' , ['id' => $userId] , ['>=' , 'money' , $absMoney]]);
            if (!$up) {
                $t->rollBack();
                return $this->setErrorByErrorCode(self::ERROR_CODE_DEDUCT_FAILURE);//扣款失败
            }
        } else if ($oprationMoney > 0) {//如果money大于0则加款
            $up = AgentUsers::updateAllCounters(['money' => $oprationMoney] , ['id' => $userId]);
            if (!$up) {
                $t->rollBack();
                return $this->setErrorByErrorCode(self::ERROR_CODE_DEPOSIT_FAILURE);//充值余额失败
            }
        }
        $t->commit();

        $this->beforeBalance             = $userBalance;
        $this->afterBalance              = $userBalance + $oprationMoney;
        $this->platformTotalAfterBalance = AgentUsers::find()->sum('money');
        return true;
    }

    /**
     * @throws \yii\base\Exception
     */
    private function check()
    {
        if (!$this->userId) {
            throw new \yii\base\Exception('非法的用户ID');
        }
        if (!$this->oprationMoney) {
            throw new \yii\base\Exception('非法的操作金额');
        }
    }

}