<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-03-24
 * Time: 14:35
 */
namespace agent_models\business\order\recharge;
use yii\base\Model;

class OrderRechargeAll extends \agent_models\database\OrderRechargeAll implements OrderConstansInterface{
    use OrderRechargeModelTrait;
    public function loadForRechargeOrderByOrderModel(Model $order , $order_cate)
    {
        $this->load($order->toArray());
        $this->order_cate = $order_cate;
        return $this;
    }
    public function rules()
    {
        return [
            [['user_id', 'parent_dealer_user_id', 'trade_sn', 'order_sn', 'order_cate', 'recharge_accounts', 'recharge_amount', 'product_id', 'product_name', 'order_status', 'recharge_status', 'user_ip', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price'], 'required'],
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'order_cate', 'product_id', 'order_status', 'recharge_status', 'user_ip'], 'integer'],
            [['recharge_amount', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price'], 'number'],
            [['add_time', 'end_time'], 'safe'],
            [['trade_sn', 'product_name'], 'string', 'max' => 255],
            [['order_sn'], 'string', 'max' => 19],
            [['recharge_accounts'], 'string', 'max' => 11],
            [['order_sn', 'order_cate'], 'unique', 'targetAttribute' => ['order_sn', 'order_cate']],
            [['trade_sn'], 'unique'],
        ];
    }

}