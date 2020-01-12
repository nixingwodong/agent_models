<?php
namespace common\models\business\order\recharge;
use common\models\traits\YiiModelTrait;

trait OrderRechargeModelTrait{
    use YiiModelTrait;
    public function loadDefaultForAddOrder(){
        $this->order_sn              = Common::buildAnOrderSn();
        $this->user_id               = \Yii::$app->user->agentUser->id;
        $this->parent_dealer_user_id = \Yii::$app->user->agentUser->parent_dealer_user_id;
        $this->employee_id           = \Yii::$app->user->getEmployeeIdIfHad();
        $this->user_ip               = self::getUserIpAsInt();
        $this->add_time              = self::getNowTime();
        $this->order_status    = self::ORDER_STATUS_RECHARGE;
        $this->recharge_status = self::RECHARGE_STATUS_WAIT_RECHARGE;
        $this->add_order_way = self::ADD_ORDER_WAY_SINGLE;
    }
}