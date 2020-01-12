<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-02
 * Time: 18:18
 */

namespace agent_models\business\finance\money;

use agent_models\business\trade\AgentUsersTradeLog;
use agent_models\database\TaobaoRechargeBalanceLog;
use agent_models\traits\GeneralModelTrait;
use agent_models\User;
use nixingwodong\cf_all\taobao\JishibaoGetTaobaoOrderDetails;
use nixingwodong\cf_all\taobao\JishibaoNotify;


/**
 * Class AgentUserMoneyRechargeByTaobao
 * @package agent_models\business\finance\money
 * @property AgentUsersTradeLog $tradeLog
 */
class AgentUserMoneyRechargeByTaobao
{
    use GeneralModelTrait;
    public $taobaoOrderSn;
    const TAOBAO_PRODUCTS_IDS           = JishibaoNotify::NEW_YIZHIBAO_AGENT_PRODUCT_IDS;
    const RECHARGE_STATUS_WAIT_RECHARGE = 0;
    const RECHARGE_STATUS_RECHARGED     = 1;

    public function __construct($taobaoOrderSn)
    {
        $this->taobaoOrderSn = $taobaoOrderSn;
    }


    /**
     * *****记得外面要开事务**********
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function recharge()
    {
        $taobao_order_sn = $this->taobaoOrderSn;
        //查询数据库是否存在该笔订单
        $taobaoLogModel = TaobaoRechargeBalanceLog::findOne(['tid' => $taobao_order_sn]);
        if (!$taobaoLogModel) {
            return $this->setErrorMsg('订单表尚未记录该淘宝订单或异常');
        }

        if ($taobaoLogModel->recharge_status != self::RECHARGE_STATUS_WAIT_RECHARGE) {
            return $this->setErrorMsg('该笔订单不允许充值');
        }
        $orderDetailsModel = new JishibaoGetTaobaoOrderDetails($taobao_order_sn);

        //获取淘宝订单详情
        if (!$orderDetailsModel->get()) {
            return $this->setErrorMsg($orderDetailsModel->getErrorMsg());
        }
        //检查金额交易状态等等
        if (!$orderDetailsModel->isCanRecharge()) {
            return $this->setErrorMsg($orderDetailsModel->getErrorMsg());
        }
        //解析账号
        if (!$orderDetailsModel->loadAccounts()) {
            return $this->setErrorMsg($orderDetailsModel->getErrorMsg());
        }
        $taobao_input_region   = $orderDetailsModel->taobao_input_region;
        $taobao_input_accounts = $orderDetailsModel->taobao_input_accounts;

        //********************************实体店账号信息检测开始**********************************************************
        //对比填写的账号
        if ($taobao_input_region == $taobao_input_region) {
            $accountsModel = User::findByUsername($taobao_input_region);
            if (!$accountsModel) {
                return $this->setErrorMsg($taobao_input_region . '账号不存在，可能加款账号填错');
            }
        } else {
            $inputRegionInfoModel   = User::findByUsername($taobao_input_region);
            $inputAccountsInfoModel = User::findByUsername($taobao_input_accounts);
            if (!$inputRegionInfoModel && !$inputAccountsInfoModel) {
                return $this->setErrorMsg('获取加款账号信息失败，可能加款账号填错');
            }
            if ($inputRegionInfoModel && $inputAccountsInfoModel) {
                if ($inputRegionInfoModel->id != $inputAccountsInfoModel->id) {
                    return $this->setErrorMsg("填了两个不一样的账号[{$inputRegionInfoModel->username}][{ $inputAccountsInfoModel->username}]");
                }
            }
            if ($inputRegionInfoModel) {
                $accountsModel = $inputRegionInfoModel;
            }
            if ($inputAccountsInfoModel) {
                $accountsModel = $inputAccountsInfoModel;
            }
        }

        //********************************实体店账号信息检测结束**********************************************************
        //*******************************开始加款***************************************
        //*****更新淘宝订单表信息
        $taobaoLogModel->tid             = $orderDetailsModel->tid;
        $taobaoLogModel->buy_num         = $orderDetailsModel->num;
        $taobaoLogModel->product_name    = $orderDetailsModel->product_title;
        $taobaoLogModel->recharge_status = self::RECHARGE_STATUS_WAIT_RECHARGE;

        $taobaoLogModel->username  = $accountsModel->username;
        $taobaoLogModel->user_id   = $accountsModel->id;
        $taobaoLogModel->real_name = $accountsModel->real_name;

        $taobaoLogModel->money                 = $orderDetailsModel->payment;
        $taobaoLogModel->seller_nick           = $orderDetailsModel->title;
        $taobaoLogModel->taobao_input_region   = $orderDetailsModel->taobao_input_region;
        $taobaoLogModel->taobao_input_accounts = $orderDetailsModel->taobao_input_accounts;
        $taobaoLogModel->order_details         = $orderDetailsModel->requestResponse;
        $taobaoLogModel->buyer_nick            = $orderDetailsModel->buyer_nick;
        $taobaoLogModel->add_time              = date('Y-m-d H:i:s');
        //*******************************加款完成***************************************
        return $this->commonSave($accountsModel , $taobaoLogModel);
    }

    /**
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    private function commonSave(User $accountsModel , TaobaoRechargeBalanceLog $taobaoLogModel , $isBackend = false)
    {

        //*********新增余额充值记录

        $rechargeLogModel                                = new AgentUserMoneyRechargeLog();
        $now                                             = self::getNowTime();
        $rechargeLogModel->user_id                       = $accountsModel->id;
        $rechargeLogModel->order_sn                      = AgentUserMoneyRechargeLog::buildAnOrderSn();
        $rechargeLogModel->money                         = $taobaoLogModel->money;
        $rechargeLogModel->payment_method                = $rechargeLogModel::PAYMENT_METHOD_TAOBAO_SHOP;
        $rechargeLogModel->payment_method_third_order_sn = $taobaoLogModel->tid;
        $rechargeLogModel->recharge_status               = $rechargeLogModel::RECHARGE_STATUS_SUCCESS;
        $rechargeLogModel->info                          = "通过淘宝订单" . $isBackend ? "" : "[{$taobaoLogModel->buyer_nick}]" . "[{$this->taobaoOrderSn}]完成余额充值{$rechargeLogModel->money}元";
        $rechargeLogModel->add_time                      = $now;
        $rechargeLogModel->end_time                      = $now;

        //*********新增交易记录
        $tradeModel = new AgentUsersTradeLog();
        $tradeModel->loadForRechargeUserBalanceByRechargeLogModel($rechargeLogModel);
        $tradeModel->trade_status   = $tradeModel::TRADE_STATUS_SUCCESS;
        $rechargeLogModel->trade_sn = $tradeModel->trade_sn;

        //********更新用户余额，即充值
        $userMoneyModel = new UpAgentUserMoney($rechargeLogModel->user_id , abs($rechargeLogModel->money));
        if (!$userMoneyModel->up()) {
            return $this->setErrorMsg('更新用户余额失败:' . $userMoneyModel->getErrorMsg());
        }

        $rechargeLogModel->user_after_balance = $userMoneyModel->afterBalance;
        $taobaoLogModel->recharge_status      = self::RECHARGE_STATUS_RECHARGED;
        $taobaoLogModel->order_sn             = $rechargeLogModel->order_sn;
        //********新增资金明细
        $addMoneyLog = new AgentUserMoneyLog();
        if (!$addMoneyLog->addByTrade($tradeModel , $userMoneyModel->afterBalance)) {
            return $this->setErrorMsg($addMoneyLog->getOneErrorMsg());
        }
        //********保存交易记录
        if (!$tradeModel->save()) {
            return $this->setErrorMsg('保存交易记录失败:' . $tradeModel->getOneErrorMsg());
        }
        //********保存充值记录


        if (!$rechargeLogModel->save()) {
            return $this->setErrorMsg('保存充值记录失败:' . $rechargeLogModel->getOneErrorMsg());
        }
        if ($isBackend) {
            $taobaoLogModelSave = $taobaoLogModel->save(true , ['tid' , 'user_id' , 'real_name' , 'order_sn' , 'username' , 'recharge_status' , 'money']);
        } else {
            $taobaoLogModelSave = $taobaoLogModel->save();
        }
        //******保存淘宝记录
        if (!$taobaoLogModelSave) {
            return $this->setErrorMsg('保存TaobaoRechargeBalanceLog失败:' . current($taobaoLogModel->getFirstErrors()));
        }
        return true;
    }

    /**
     * @param $userModel
     * @param $money
     * @return bool
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function rechargeByBackend(User $userModel , $money)
    {
        //查询数据库是否存在该笔订单
        $taobaoLogModel = TaobaoRechargeBalanceLog::findOne(['tid' => $this->taobaoOrderSn]);
        if (!$taobaoLogModel) {
            $taobaoLogModel = new TaobaoRechargeBalanceLog();
        }
        if ($taobaoLogModel->recharge_status == self::RECHARGE_STATUS_RECHARGED) {
            return $this->setErrorMsg('该笔订单已处理过，如未到账请联系技术人员');
        }
        $taobaoLogModel->tid       = $this->taobaoOrderSn;
        $taobaoLogModel->money     = $money;
        $taobaoLogModel->user_id   = $userModel->id;
        $taobaoLogModel->username  = $userModel->username;
        $taobaoLogModel->real_name = $userModel->username;
        // $taobaoLogModel->recharge_status= self::RECHARGE_STATUS_RECHARGED;

        return $this->commonSave($userModel , $taobaoLogModel , true);
    }
}