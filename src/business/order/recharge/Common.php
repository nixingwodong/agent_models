<?php

namespace agent_models\business\order\recharge;

use agent_models\business\finance\money\AgentUserMoneyPayByTradeLog;
use agent_models\business\trade\AgentUsersTradeLog;
use agent_models\traits\GeneralModelTrait;
use yii\base\ErrorException;
use yii\base\Model;
use yii\helpers\Url;

class Common
{
    use GeneralModelTrait;
    const ORDER_STATUS_UNKNOWN  = 0;//未知
    const ORDER_STATUS_RECHARGE = 1;//充值中
    const ORDER_STATUS_SUCCESS  = 2;//充值成功
    const ORDER_STATUS_FAILURE  = 3;//充值失败
    const ORDER_STATUS_CANCEL   = 4;//已取消


    const ORDER_CATE_MOBILE_RECHARGE                    = 1;
    const ORDER_CATE_FLOW_RECHARGE                      = 2;
    const ORDER_CATE_TELEPHONE_RECHARGE                 = 3;
    const ORDER_CATE_TENCENT_RECHARGE                   = 4;
    const ORDER_CATE_GAME_RECHARGE                      = 5;
    const ORDER_CATE_ACCOUNTS_BALANCE_RECHARGE          = 100;
    const ORDER_CATE_AGENT_USER_MONEY_TRANSFER_ACCOUNTS = 101;


    const ADD_ORDER_WAY_UNKNOWN       = 0;//未知
    const ADD_ORDER_WAY_SINGLE_STROKE = 1;//单笔
    const ADD_ORDER_WAY_BATCH         = 2;//批量

    const UNIT_INDIVIDUAL = 1;//个
    const UNIT_MONTH      = 2;//月
    const UNIT_RMB        = 3;//元，人民币


    public static $recharge_status
        = ['0' => '未知' , '1' => '待付款' , '2' => '等待提单' , '3' => '充值中' , '4' => '提交失败' , '5' => '充值成功' ,     //充值状态
           '6' => '充值失败' , '7' => '已退款' , '8' => '充值可疑' , '9' => '已提单' , '10' => '提交可疑' , '11' => '已验单' , '12' => '已取消' , '13' => '待处理'];


    public static $unit = [1 => '个' , 2 => '月' , 3 => '元'];//充值单位


    public static function get_order_status()
    {
        return [
            //   self::ORDER_STATUS_UNKNOWN=>'未知',
            self::ORDER_STATUS_RECHARGE => '充值中' ,
            self::ORDER_STATUS_SUCCESS  => '充值成功' ,
            self::ORDER_STATUS_FAILURE  => '充值失败' ,
            self::ORDER_STATUS_CANCEL   => '已取消' ,
        ];
    }

    public static function get_add_order_way()
    {
        return [
            //  self::add_order_way_unknown=>'未知',
            self::ADD_ORDER_WAY_SINGLE_STROKE => '单笔充值' ,
            self::ADD_ORDER_WAY_BATCH         => '批量充值' ,
        ];
    }


    public function get_unit()
    {
        return [
            self::UNIT_INDIVIDUAL => '个' ,
            self::UNIT_MONTH      => '月' ,
            self::UNIT_RMB        => '元' ,
        ];
    }


    public static function get_order_model_by_order_cate($order_cate)
    {
        $order_cates = self::get_order_cates();
        if (empty($order_cates[$order_cate]['order_model'])) {
            throw new ErrorException('该order_cate尚未赋值order_model');
        }
        return $order_cates[$order_cate]['order_model'];
    }

    public static function edit_user_retail_price_by_order_cate($order_cate , $order_sn , $new_price , $user_id)
    {
        $t           = \Yii::$app->db->beginTransaction();
        $order_cates = self::get_order_cates();

        $order = ($order_cates[$order_cate]['order_model'])::findOne(['order_sn' => $order_sn , 'user_id' => $user_id]);
        if (!$order) {
            return ['status' => false , 'msg' => '订单不存在'];
        }
        $date                     = date('Y-m-d' , strtotime($order->add_time));
        $old_user_retail_price    = $order->user_retail_price;
        $old_profit               = $old_user_retail_price - $order->total_price;//旧利润
        $new_profit               = $new_price - $order->total_price;            //新利润
        $order->user_retail_price = $new_price;                                  //更改新价格
        $profit                   = AgentUsersEverydayTradeCount::findOne(['user_id' => $user_id , 'date' => $date]) ?: new AgentUsersEverydayTradeCount();
        $profit->user_id          = $order->user_id;
        $profit->date             = $date;
        $profit->profit           = ($profit->profit - $old_profit) + $new_profit;

        $add_operation_log = new AgentUsersOperationLog();
        $add_operation_log->load([
            'user_id'           => $order->user_id ,
            'operation_user_id' => $order->user_id ,
            'log_type'          => AgentUsersOperationLog::log_type_edit_user_retail_price ,
            'info'              => "修改{$order_cates[$order_cate]['name']}订单[{$order_sn}]零售价格为{$new_price}元" ,
        ] , '');

        if (!$order->save() || !$profit->save() || !$add_operation_log->save() || !OrderRechargeAll::updateAll(['user_retail_price' => $new_price] , ['order_sn' => $order_sn , 'order_cate' => $order_cate])) {
            $t->rollBack();
            return ['status' => false , 'msg' => '操作失败，未更改新的价格' , 'profit' => $profit->getFirstErrors() , 'order' => $order->getFirstErrors() , 'add_operation_log' => $add_operation_log->getFirstErrors()];
        }
        $t->commit();
        return ['status' => true , 'msg' => '操作成功'];
    }


    public static function buildAnOrderSn($prefix = '')
    {
        return $prefix . ltrim(date('YmdHis') , '20') . str_pad(mt_rand(1 , 99999) , 5 , '0' , STR_PAD_LEFT);
    }

    public static function checkArbitrarilyCanBuyScope($can_buy_scope , $face_value , $recharge_amount)
    {
        $limits = explode(',' , $can_buy_scope);
        foreach ($limits as $v) {
            if (is_numeric($v)) {
                if ($recharge_amount == ($v * $face_value)) {
                    return true;
                }
            } else {
                $limit = explode('-' , $v);
                if (count($limit) == 1) {
                    if (is_numeric($limit[0]) && $recharge_amount == ($limit[0])) {//左边范围*面值=
                        return true;
                    }
                } else if (count($limit) == 2) {
                    asort($limit);
                    if (is_numeric($limit[0]) && is_numeric($limit[1])) {
                        if ($recharge_amount >= ($limit[0]) && $recharge_amount <= ($limit[1])) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function add_profit_by_order_model_when_add_order(Model $order_m)
    {
        $profit_date          = date('Y-m-d' , strtotime($order_m->add_time));
        $add_profit           = AgentUsersEverydayTradeCount::findOne(['user_id' => $order_m->user_id , 'date' => $profit_date]) ?: new AgentUsersEverydayTradeCount();
        $add_profit->user_id  = $order_m->user_id;
        $add_profit->profit   = $add_profit->profit + $order_m->user_retail_price - $order_m->total_price;
        $add_profit->date     = $profit_date;
        $add_profit->add_time = $order_m->add_time;
        return $add_profit;
    }


    //原订单状态为充值中，置成功或置失败
    public static function edit_order_status_by_order_sn_for_notify($order_sn , $new_status)
    {
        $t = \Yii::$app->db->beginTransaction();
        if (!$order_sn) {
            return ['status' => false , 'msg' => '订单编号不能为空'];
        }
        if (!in_array($new_status , [self::order_status_success , self::order_status_failure])) {
            return ['status' => false , 'msg' => '非法的订单状态'];
        }
        $order_all = OrderRechargeAll::findOne(['order_sn' => $order_sn]);
        if (!$order_all) {
            return ['status' => false , 'msg' => '订单不存在'];
        }
        $order = (self::get_order_model_by_order_cate($order_all->order_cate))::findOne(['order_sn' => $order_sn]);
        if (!$order) {
            return ['status' => false , 'msg' => '订单不存在'];
        }
        //不是充值中的订单不能修改
        if ($order_all->order_status == $order->order_status && ($order_all->order_status == $new_status || $order_all->order_status != self::order_status_recharge)) {
            return ['status' => false , 'msg' => '订单状态非充值中，不可更改'];
        }
        $trade = AgentUsersMoneyTradeLog::findOne(['trade_sn' => $order->trade_sn]);
        if (!$trade) {
            return ['status' => false , 'msg' => '该笔交易记录不存在或存在异常，请联系客服处理'];
        }

        $order->order_status     = $new_status;
        $order_all->order_status = $new_status;
        if (!$order_all->save() || !$order->save()) {
            $t->rollBack();
            return ['status' => false , 'msg' => '操作失败' , 'order_all' => $order_all->getFirstErrors() , 'order' => $order->getFirstErrors()];
        }
        if ($new_status == self::order_status_success) {
            //利润统计表
            $date            = date('Y-m-d' , strtotime($order->add_time));
            $user_profit     = $order->user_retail_price - $order->total_price;
            $profit          = AgentUsersEverydayTradeCount::findOne(['user_id' => $order->user_id , 'date' => $date]) ?: new AgentUsersEverydayTradeCount();
            $profit->user_id = $order->user_id;
            $profit->date    = $date;
            $profit->profit  = ($profit->profit ?: 0) + $user_profit;
            if (!$profit->save()) {
                $t->rollBack();
                return ['status' => false , 'msg' => '操作失败，profit记录失败' , 'profit' => $profit->getFirstErrors()];
            }
            //返利
            if ($order_all->parent_dealer_user_id != 0 && $order_all->rebate != 0) {
                $add_rebate = AgentUsersRebateBalance::add_rebate($order_all , AgentUsersRebateLog::rebate_log_type_order_rebate , "{$order_all->product_name}[贡献返利]");
                if (!$add_rebate['status']) {
                    $t->rollBack();
                    return $add_rebate;
                }
            }

            $new_trade_status = AgentUsersMoneyTradeLog::trade_status_success;
        } else {//失败，要退款
            $refund = AgentUserMoney::refund($trade , abs($order->total_price));
            if (!$refund['status']) {
                $t->rollBack();
                return $refund;
            }
            $new_trade_status = AgentUsersMoneyTradeLog::trade_status_failure;
        }
        $trade->trade_status = $new_trade_status;
        //交易记录更新
        if (!$trade->save()) {
            $t->rollBack();
            return ['status' => false , 'msg' => '操作失败，交易记录状态更新失败' , 'trade' => $trade->getFirstErrors()];
        }

        $t->commit();
        return ['status' => true , 'msg' => '操作成功'];
    }

    //成功转失败
    public static function order_refund($order_sn)
    {
        $t = \Yii::$app->db->beginTransaction();

        $order_all = OrderRechargeAll::findOne(['order_sn' => $order_sn]);
        if (!$order_all) {
            $t->rollBack();
            return ['status' => false , 'msg' => '订单不存在'];
        }
        $order = (self::get_order_model_by_order_cate($order_all->order_cate))::findOne(['order_sn' => $order_sn]);
        if (!$order) {
            $t->rollBack();
            return ['status' => false , 'msg' => '订单不存在'];
        }
        if ($order_all->order_status != $order->order_status) {
            $t->rollBack();
            return ['status' => false , 'msg' => "order_all订单状态{$order_all->order_status}和order订单状态{$order_all->order_status}不一致"];
        }
        if ($order_all->order_status != self::order_status_success) {
            $t->rollBack();
            return ['status' => false , 'msg' => '非成功订单状态，不可退款'];
        }

        $trade = AgentUsersMoneyTradeLog::findOne(['trade_sn' => $order->trade_sn]);
        if (!$trade) {
            $t->rollBack();
            return ['status' => false , 'msg' => '交易记录不存在'];
        }
        //退款，待写退佣金的
        $refund = AgentUserMoney::refund($trade , abs($order->total_price));
        if (!$refund['status']) {
            $t->rollBack();
            return $refund;
        }

        if ($order_all->parent_dealer_user_id != 0 && $order_all->rebate != 0) {
            $refund_rebate = AgentUsersRebateBalance::refund_rebate($order_all , AgentUsersRebateLog::rebate_log_type_order_refund , "{$order_all->product_name}[退款返利]" , $order_all->rebate);
            if (!$refund_rebate['status']) {
                $t->rollBack();
                return $refund_rebate;
            }
        }
        //利润统计表
        $date            = date('Y-m-d' , strtotime($order->add_time));
        $user_profit     = $order->user_retail_price - $order->total_price;
        $profit          = AgentUsersEverydayTradeCount::findOne(['user_id' => $order->user_id , 'date' => $date]) ?: new AgentUsersEverydayTradeCount();
        $profit->user_id = $order->user_id;
        $profit->date    = $date;
        $profit->profit  = ($profit->profit ?: 0) - $user_profit;

        $order->order_status     = self::order_status_failure;
        $order_all->order_status = self::order_status_failure;
        $trade->trade_status     = AgentUsersMoneyTradeLog::trade_status_failure;

        if (!$order_all->save() || !$order->save() || !$profit->save() || !$trade->save()) {
            $t->rollBack();
            return ['status' => false , 'msg' => '操作失败' , 'order_all' => $order_all->getFirstErrors() , 'order' => $order->getFirstErrors() , 'profit' => $profit->getFirstErrors() , 'trade' => $trade->getFirstErrors()];
        }
        $t->commit();
        return ['status' => true , 'msg' => '操作成功'];
    }


    /**
     * @param Model $formModel
     * @param \yii\db\ActiveRecord $orderModel
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function saveOrder(Model $formModel , \yii\db\ActiveRecord $orderModel)
    {
        //录入交易记录表
        $tradeLogModel = (new AgentUsersTradeLog())->loadForAddRechargeOrderByOrderModel($orderModel , $orderModel->orderCate);

        //保存订单
        $orderModel->trade_sn = $tradeLogModel->trade_sn;
        if (!$orderModel->save()) {
            $formModel->addError('add_order' , '下单失败' . $orderModel->getOneErrorMsg());
            return false;
        }
        //录入全部订单表
        $orderAllModel = (new OrderRechargeAll())->loadForRechargeOrderByOrderModel($orderModel , $orderModel->orderCate);
        if (!$orderAllModel->save()) {
            $formModel->addError('add_order' , '创建公共订单失败' . $orderAllModel->getOneErrorMsg());
            return false;
        }

        //新增交易记录
        if (!$tradeLogModel->save()) {
            $formModel->addError('add_order' , '创建交易记录失败' . $tradeLogModel->getOneErrorMsg());
            return false;
        }
        //支付
        $pay = (new AgentUserMoneyPayByTradeLog($tradeLogModel));
        if (!$pay->pay()) {
            $formModel->addError('add_order' , $pay->getErrorMsg());
            return false;
        }
        return true;
    }

    /**
     * @param $order_sn
     * @param $new_price
     * @return array
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public static function editUserRetailByOrderSn($order_sn , $new_price)
    {
        if (!$new_price || !$order_sn || !is_numeric($new_price)) {
            return ['status' => false , 'msg' => '非法操作'];
        }
        $t        = \Yii::$app->db->beginTransaction();
        $orderAll = OrderRechargeAll::findOne(['order_sn' => $order_sn]);
        if (!$orderAll) {
            $t->rollBack();
            return ['status' => false , 'msg' => '修改失败' , 'e' => '主订单不存在'];
        }
        $orderModel = AgentUsersTradeLog::gerOrderModelByCate($orderAll->order_cate);
        $order      = $orderModel::findOne(['order_sn' => $order_sn]);
        if (!$order) {
            $t->rollBack();
            return ['status' => false , 'msg' => '修改失败' , 'e' => '子订单不存在'];
        }
        if ($orderAll->user_retail_price == $new_price && $order->user_retail_price == $new_price) {
            return ['status' => false , 'msg' => '没有修改'];
        }

        $orderAll->user_retail_price = $new_price;
        $order->user_retail_price    = $new_price;
        if (!$orderAll->save()) {
            $t->rollBack();
            return ['status' => false , 'msg' => '修改失败' , 'e' => $orderAll->getFirstErrors()];
        }
        if (!$order->save()) {
            $t->rollBack();
            return ['status' => false , 'msg' => '修改失败' , 'e' => $order->getFirstErrors()];
        }
        $t->commit();
        return ['status' => true , 'msg' => '修改成功'];
    }

}