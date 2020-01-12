<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-02
 * Time: 22:41
 */

namespace common\models\business\trade;

use common\models\business\finance\money\AgentUserMoneyLog;
use common\models\business\finance\money\AgentUsersMoneyTransferAccountsLog;
use common\models\database\AgentUsersMoneyRechargeLog;
use common\models\traits\YiiModelTrait;
use common\models\User;
use yii\base\Exception;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\Url;

class AgentUsersTradeLog extends \common\models\database\AgentUsersTradeLog
{
    use YiiModelTrait;

    const TRADE_STATUS_UNKNOWN  = 0;//未知
    const TRADE_STATUS_WAIT_PAY = 1;//待付款
    const TRADE_STATUS_TRADING  = 2;//交易中
    const TRADE_STATUS_SUCCESS  = 3;//交易完成
    const TRADE_STATUS_FAILURE  = 4;//交易失败
    const TRADE_STATUS_CANCEL   = 5;//交易取消

    const TRADE_LOG_TYPE_UNKNOWN           = 0;//未知
    const TRADE_LOG_TYPE_PAY_ORDER         = 1;//订单支付
    const TRADE_LOG_TYPE_REFUND            = 2;//退款
    const TRADE_LOG_TYPE_CASH_WITHDRAWAL   = 3;//提现
    const TRADE_LOG_TYPE_RECHARGE_BALANCE  = 4;//余额充值
    const TRADE_LOG_TYPE_TRANSFER_ACCOUNTS = 5;//转账

    /*收支方式*/
    const INOUT_TYPE_IN  = 1;//收入
    const INOUT_TYPE_OUT = 2;//支出


    const PAYMENT_METHOD_UNKNOWN         = 0;//其他,未知
    const PAYMENT_METHOD_BALANCE         = 1;//余额支付
    const PAYMENT_METHOD_ALIPAY_TRANSFER = 2;//支付宝转账
    const PAYMENT_METHOD_ALIPAY_PC       = 3;//支付宝在线PC
    const PAYMENT_METHOD_TAOBAO_SHOP     = 4;//淘宝店铺
    const PAYMENT_METHOD_WECHAT          = 5;//微信
    const PAYMENT_METHOD_CASH            = 6;//现金
    const PAYMENT_METHOD_WECHAT_TRANSFER = 7;//微信转账


    const TRADE_STATUES
        = [
            self::TRADE_STATUS_UNKNOWN  => '未知' ,
            self::TRADE_STATUS_WAIT_PAY => '待付款' ,
            self::TRADE_STATUS_TRADING  => '交易中' ,
            self::TRADE_STATUS_SUCCESS  => '交易完成' ,
            self::TRADE_STATUS_FAILURE  => '交易失败' ,
            self::TRADE_STATUS_CANCEL   => '交易取消' ,
        ];

    const TRADE_LOG_TYPES
        = [
            self::TRADE_LOG_TYPE_UNKNOWN           => '未知' ,
            self::TRADE_LOG_TYPE_PAY_ORDER         => '订单支付' ,
            self::TRADE_LOG_TYPE_REFUND            => '退款' ,
            self::TRADE_LOG_TYPE_CASH_WITHDRAWAL   => '提现' ,
            self::TRADE_LOG_TYPE_RECHARGE_BALANCE  => '余额充值' ,
            self::TRADE_LOG_TYPE_TRANSFER_ACCOUNTS => '转账' ,
        ];

    const INOUT_TYPES
        = [
            self::INOUT_TYPE_IN  => '收入' ,
            self::INOUT_TYPE_OUT => '支出' ,
        ];


    const ORDER_CATE_RECHARGE_MOBILE                    = 100;
    const ORDER_CATE_RECHARGE_FLOW                      = 200;
    const ORDER_CATE_RECHARGE_TELEPHONE                 = 300;
    const ORDER_CATE_RECHARGE_TENCENT                   = 400;
    const ORDER_CATE_RECHARGE_GAME                      = 500;
    const ORDER_CATE_AGENT_USER_MONEY_BALANCE_RECHARGE  = 600;
    const ORDER_CATE_AGENT_USER_MONEY_TRANSFER_ACCOUNTS = 700;

    public static function getOrderCates()
    {
        return [
            self::ORDER_CATE_RECHARGE_MOBILE    => [
                'name'  => '手机话费充值' , 'order_model' => \common\models\database\OrderMobileRecharge::class ,
                'route' => [
                    'order_details' => '/order/mobile-recharge/details' ,
                ] ,
            ] ,
            self::ORDER_CATE_RECHARGE_FLOW      => [
                'name'  => '手机流量充值' , 'order_model' => \common\models\database\OrderMobileRecharge::class ,
                'route' => [
                    'order_details' => '/order/flow-recharge/details' ,
                ] ,
            ] ,
            self::ORDER_CATE_RECHARGE_TELEPHONE => [
                'name'  => '固话宽带充值' , 'order_model' => \common\models\database\OrderTelephoneRecharge::class ,
                'route' => [
                    'order_details' => '/order/telephone-recharge/details' ,
                ] ,
            ] ,
            self::ORDER_CATE_RECHARGE_TENCENT   => [
                'name'  => '腾讯业务充值' , 'order_model' => \common\models\database\OrderTencentRecharge::class ,
                'route' => [
                    'order_details' => '/order/tencent-recharge/details' ,
                ] ,
            ] ,
            self::ORDER_CATE_RECHARGE_GAME      => [
                'name'  => '游戏点卡充值' , 'order_model' => \common\models\database\OrderGameRecharge::class ,
                'route' => [
                    'order_details' => '/order/game-recharge/details' ,
                ] ,
            ] ,

            self::ORDER_CATE_AGENT_USER_MONEY_BALANCE_RECHARGE  => ['name' => '账户余额充值'] ,
            self::ORDER_CATE_AGENT_USER_MONEY_TRANSFER_ACCOUNTS => ['name' => '转账'] ,
        ];
    }

    public static function getOrderCateNamesForOrderRecharge()
    {
        return [
            self::ORDER_CATE_RECHARGE_MOBILE    => '话费' ,
            self::ORDER_CATE_RECHARGE_FLOW      => '流量' ,
            self::ORDER_CATE_RECHARGE_TELEPHONE => '固话' ,
            self::ORDER_CATE_RECHARGE_TENCENT   => '腾讯' ,
            self::ORDER_CATE_RECHARGE_GAME      => '游戏' ,
        ];
    }

    public static function getOrderCateNames()
    {
        foreach (self::getOrderCates() as $k => $v) {
            $data[$k] = $v['name'];
        }
        return $data;
    }


    /**
     * @param $orderCate
     * @return ActiveRecord
     * @throws Exception
     */
    public static function gerOrderModelByCate($orderCate)
    {
        $orderCates = AgentUsersTradeLog::getOrderCates();
        if (empty($orderCates[$orderCate]['order_model'])) {
            throw new Exception('订单模型不存在');
        }
        return $orderCates[$orderCate]['order_model'];
    }

    public static function getPaymentMethod()
    {
        return [
            self::PAYMENT_METHOD_UNKNOWN         => [
                'id'   => self::PAYMENT_METHOD_UNKNOWN ,
                'name' => '其他' ,
            ] ,
            self::PAYMENT_METHOD_BALANCE         => [
                'id'         => self::PAYMENT_METHOD_BALANCE ,
                'name'       => '余额支付' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
            ] ,
            self::PAYMENT_METHOD_ALIPAY_TRANSFER => [
                'id'         => self::PAYMENT_METHOD_ALIPAY_TRANSFER ,
                'name'       => '支付宝转账' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
            ] ,
            self::PAYMENT_METHOD_ALIPAY_PC       => [
                'id'         => self::PAYMENT_METHOD_ALIPAY_PC ,
                'name'       => '支付宝在线PC' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
                'poundage'   => ['rate' => 0.006 , 'rate_unit' => 1] ,//默认手续费
            ] ,
            self::PAYMENT_METHOD_TAOBAO_SHOP     => [
                'id'           => self::PAYMENT_METHOD_TAOBAO_SHOP ,
                'name'         => '淘宝店铺' ,
                'modelsName'   => '\common\models\money\recharge\way\AlipayTransfer' ,
                'pintai_power' => [1 , 2 , 3] ,//平台
            ] ,
            self::PAYMENT_METHOD_WECHAT          => [
                'id'         => self::PAYMENT_METHOD_WECHAT ,
                'name'       => '微信' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
            ] ,
            self::PAYMENT_METHOD_WECHAT_TRANSFER => [
                'id'         => self::PAYMENT_METHOD_WECHAT_TRANSFER ,
                'name'       => '微信转账' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
            ] ,
            self::PAYMENT_METHOD_CASH            => [
                'id'         => self::PAYMENT_METHOD_CASH ,
                'name'       => '现金' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
            ] ,
        ];
    }


    public static function getUserBalanceRechargeMethods()
    {
        return [
            self::PAYMENT_METHOD_ALIPAY_TRANSFER => [
                'id'         => self::PAYMENT_METHOD_ALIPAY_TRANSFER ,
                'name'       => '支付宝转账' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
                'enName'     => 'alipayTransfer' ,
            ] ,
            self::PAYMENT_METHOD_ALIPAY_PC       => [
                'id'         => self::PAYMENT_METHOD_ALIPAY_PC ,
                'name'       => '支付宝在线PC' ,
                'enName'     => 'alipayPc' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
            ] ,
            self::PAYMENT_METHOD_TAOBAO_SHOP     => [
                'id'     => self::PAYMENT_METHOD_TAOBAO_SHOP ,
                'name'   => '淘宝店铺' ,
                'enName' => 'taobaoShop' ,
            ] ,
            self::PAYMENT_METHOD_WECHAT_TRANSFER => [
                'id'         => self::PAYMENT_METHOD_WECHAT_TRANSFER ,
                'name'       => '微信转账' ,
                'modelsName' => '\common\models\money\recharge\way\AlipayTransfer' ,
            ] ,
            self::PAYMENT_METHOD_WECHAT          => [
                'id'     => self::PAYMENT_METHOD_WECHAT ,
                'name'   => '微信扫码或在线支付' ,
                'enName' => 'wechat' ,
            ] ,
            self::PAYMENT_METHOD_CASH            => [
                'id'     => self::PAYMENT_METHOD_CASH ,
                'name'   => '现金' ,
                'enName' => 'cash' ,
            ] ,
        ];
    }


    public static function getAlipayTransferReceivingAccounts()
    {
        return [
            ['accounts' => '15678527068' , 'name' => '刘超富' , 'nickname' => '观众13号'] ,
            ['accounts' => '1zbao@1zbao.com' , 'name' => '广西你行我动科技有限公司' , 'nickname' => '广西你行我动科技有限公司'] ,
        ];
    }

    public static function getWechatTransferReceivingAccounts()
    {
        return [
            ['accounts' => '15678527068' , 'name' => '刘超富' , 'nickname' => '好名字'] ,
        ];
    }

    public static function buildATradeSn()
    {
        return ltrim(date('YmdHis') , '20') . str_pad(substr(microtime(true) , 11 , 7) . mt_rand(1 , 99999) , 13 , '0' , STR_PAD_LEFT);
    }


    public function loadForAddRechargeOrderByOrderModel($orderModel , $orderCate)
    {
        $now            = $orderModel->add_time;
        $this->trade_sn = AgentUsersTradeLog::buildATradeSn();
        $this->order_sn = $orderModel->order_sn;


        $this->user_id      = $orderModel->user_id;
        $this->name         = $orderModel->product_name;
        $this->add_time     = $now;
        $this->pay_time     = $now;
        $this->end_time     = $now;
        $this->trade_status = AgentUsersTradeLog::TRADE_STATUS_TRADING;
        $this->info         = "支付订单[{$orderModel->order_sn}]充值号码[{$orderModel->recharge_accounts}]充值金额[{$orderModel->recharge_amount}]";
        $this->money        = -abs($orderModel->total_price);

        $this->order_cate     = $orderCate;
        $this->payment_method = AgentUsersTradeLog::PAYMENT_METHOD_BALANCE;
        $this->inout_type     = AgentUsersTradeLog::INOUT_TYPE_OUT;
        $this->trade_log_type = AgentUsersTradeLog::TRADE_LOG_TYPE_PAY_ORDER;
        $this->money_log_type = AgentUserMoneyLog::MONEY_LOG_TYPE_PAY_ORDER;

        return $this;
    }


    public function loadForRechargeUserBalanceByRechargeLogModel(AgentUsersMoneyRechargeLog $rechargeLogModel)
    {
        $now            = $rechargeLogModel->add_time;
        $this->trade_sn = AgentUsersTradeLog::buildATradeSn();
        $this->order_sn = $rechargeLogModel->order_sn;


        $this->user_id      = $rechargeLogModel->user_id;
        $this->name         = '余额充值';
        $this->add_time     = $now;
        $this->pay_time     = $now;
        $this->end_time     = $now;
        $this->trade_status = AgentUsersTradeLog::TRADE_STATUS_TRADING;
        $this->info         = "余额充值[{$rechargeLogModel->order_sn}]充值金额[{$rechargeLogModel->money}]";
        $this->money        = abs($rechargeLogModel->money);

        $this->order_cate     = AgentUsersTradeLog::ORDER_CATE_AGENT_USER_MONEY_BALANCE_RECHARGE;
        $this->payment_method = $rechargeLogModel->payment_method;
        $this->inout_type     = AgentUsersTradeLog::INOUT_TYPE_IN;
        $this->trade_log_type = AgentUsersTradeLog::TRADE_LOG_TYPE_RECHARGE_BALANCE;
        $this->money_log_type = AgentUserMoneyLog::MONEY_LOG_TYPE_RECHARGE_BALANCE;

        return $this;
    }


    public function loadForTransferByModel(AgentUsersMoneyTransferAccountsLog $logModel , User $incomeUser)
    {
        $now                  = $logModel->add_time;
        $this->trade_sn       = self::buildATradeSn();
        $this->order_sn       = $logModel->order_sn;
        $this->user_id        = $logModel->pay_user_id;
        $this->money          = -abs($logModel->money);
        $this->name           = "转账{$logModel->money}元给{$incomeUser->real_name}";
        $this->info           = "支付转账订单[{$logModel->order_sn}]";
        $this->payment_method = self::PAYMENT_METHOD_BALANCE;
        $this->inout_type     = self::INOUT_TYPE_OUT;
        $this->order_cate     = self::ORDER_CATE_AGENT_USER_MONEY_TRANSFER_ACCOUNTS;
        $this->trade_status   = self::TRADE_STATUS_SUCCESS;
        $this->trade_log_type = self::TRADE_LOG_TYPE_TRANSFER_ACCOUNTS;
        $this->money_log_type = AgentUserMoneyLog::MONEY_LOG_TYPE_TRANSFER;
        $this->add_time       = $now;
        $this->pay_time       = $now;
        $this->end_time       = $now;
        return $this;

    }

    public static function getOrderDetailsUrlByCate($orderCate , $order_sn)
    {
        $order_cates = self::getOrderCates();
        if (isset($order_cates[$orderCate]['route']['order_details'])) {
            return Url::to(['/' . $order_cates[$orderCate]['route']['order_details'] , 'order_sn' => $order_sn]);
        }
        return null;
    }


}