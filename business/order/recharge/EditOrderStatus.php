<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-01
 * Time: 5:45
 */

namespace common\models\business\order\recharge;

use common\models\business\finance\money\AgentUserMoneyRefundByTradeLog;
use common\models\business\finance\money\UpAgentUserMoney;
use common\models\business\trade\AgentUsersTradeLog;
use common\models\traits\GeneralModelTrait;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * Class EditOrderStatus
 * @package common\models\business\order\recharge
 * @property ActiveRecord $orderModel
 * @property OrderRechargeAll $order
 */
class EditOrderStatus
{
    use GeneralModelTrait;
    public $orderModel;
    public $order;
    public $order_sn;
    public $order_cate;

    /**
     * EditOrderStatus constructor.
     * @param $order_sn
     * @param $order_cate
     * @throws Exception
     */
    public function __construct($order_sn , $order_cate)
    {
        $this->order_sn   = $order_sn;
        $this->order_cate = $order_cate;
        $this->orderModel = AgentUsersTradeLog::gerOrderModelByCate($order_cate);
        $this->order      = $this->orderModel::find()->where(['order_sn' => $order_sn])->one();
        if (!$this->order) {
            throw new Exception("订单{$order_sn}不存在");
        }

    }

    /**
     * 以下2种情况，可修改
     * 1.当原订单状态为充值中
     * 2.当原订单状态为失败(需扣款)
     * @return bool
     */
    public function toSuccess()
    {
        $orderStatusSuccess = OrderRechargeAll::ORDER_STATUS_SUCCESS;
        $tradeStatusSuccess = AgentUsersTradeLog::TRADE_STATUS_SUCCESS;
        $now                = self::getNowTime();
        switch ($this->order->order_status) {
            case OrderRechargeAll::ORDER_STATUS_RECHARGE://原订单状态为充值中
                $upOrder    = $this->orderModel::updateAll(['order_status' => $orderStatusSuccess , 'end_time' => $now] , ['order_sn' => $this->order_sn]);
                $upOrderAll = OrderRechargeAll::updateAll(['order_status' => $orderStatusSuccess , 'end_time' => $now] , ['order_sn' => $this->order_sn]);
                $upTrade    = AgentUsersTradeLog::updateAll(['trade_status' => $tradeStatusSuccess , 'end_time' => $now] , ['trade_sn' => $this->order->trade_sn]);

                break;
            case OrderRechargeAll::ORDER_STATUS_FAILURE://原订单状态为充值失败
                //扣款用户,未新增扣款记录
                $moneyModel = new UpAgentUserMoney($this->order->user_id , -abs($this->order->total_price));
                if (!$moneyModel->up()) {
                    $this->setErrorMsg('扣取用户余额失败' . $moneyModel->getErrorMsg());
                    return false;
                }
                $upOrder    = $this->orderModel::updateAll(['order_status' => $orderStatusSuccess] , ['order_sn' => $this->order_sn]);
                $upOrderAll = OrderRechargeAll::updateAll(['order_status' => $orderStatusSuccess] , ['order_sn' => $this->order_sn]);
                $upTrade    = AgentUsersTradeLog::updateAll(['trade_status' => $tradeStatusSuccess] , ['trade_sn' => $this->order->trade_sn]);

                break;
            default:
                $this->setErrorMsg('该订单状态无法更改为成功');
                return false;
        }

        if (!$upOrder) {
            $this->setErrorMsg('更新订单失败');
            return false;
        }
        if (!$upOrderAll) {
            $this->setErrorMsg('更新主订单失败');
            return false;
        }
        if (!$upTrade) {
            $this->setErrorMsg('更新交易记录失败');
            return false;
        }
        return true;
    }

    /**
     * 以下2种情况，可修改
     * 1.当原订单状态为充值中(需退款)
     * 2.当原订单状态为成功(需退款)
     * @return bool
     */
    public function toFailure()
    {
        $orderStatusFailure = OrderRechargeAll::ORDER_STATUS_FAILURE;
        $tradeStatusFailure = AgentUsersTradeLog::TRADE_STATUS_FAILURE;
        $now                = self::getNowTime();

        switch ($this->order->order_status) {
            case OrderRechargeAll::ORDER_STATUS_RECHARGE://原订单为充值中
                $upOrder    = $this->orderModel::updateAll(['order_status' => $orderStatusFailure , 'end_time' => $now] , ['order_sn' => $this->order_sn]);
                $upOrderAll = OrderRechargeAll::updateAll(['order_status' => $orderStatusFailure , 'end_time' => $now] , ['order_sn' => $this->order_sn]);
                $upTrade    = AgentUsersTradeLog::updateAll(['trade_status' => $tradeStatusFailure , 'end_time' => $now] , ['trade_sn' => $this->order->trade_sn]);
                break;
            case OrderRechargeAll::ORDER_STATUS_SUCCESS://原订单为充值成功
                $upOrder    = $this->orderModel::updateAll(['order_status' => $orderStatusFailure] , ['order_sn' => $this->order_sn]);
                $upOrderAll = OrderRechargeAll::updateAll(['order_status' => $orderStatusFailure] , ['order_sn' => $this->order_sn]);
                $upTrade    = AgentUsersTradeLog::updateAll(['trade_status' => $tradeStatusFailure] , ['trade_sn' => $this->order->trade_sn]);
                break;
            default:
                $this->setErrorMsg('该订单状态无法更改为失败');
                return false;
        }


        if (!$upOrder) {
            $this->setErrorMsg('更新订单失败');
            return false;
        }
        if (!$upOrderAll) {
            $this->setErrorMsg('更新主订单失败');
            return false;
        }
        if (!$upTrade) {
            $this->setErrorMsg('更新交易记录失败');
            return false;
        }
        $trade       = AgentUsersTradeLog::findOne(['trade_sn' => $this->order->trade_sn]);
        $refundModel = new AgentUserMoneyRefundByTradeLog($trade , $this->order->total_price);
        if (!$refundModel->refund()) {
            $this->setErrorMsg($refundModel->getErrorMsg());
            return false;
        }
        return true;
    }


}