<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-01
 * Time: 5:45
 */
namespace common\models\business\order\recharge;
use common\models\business\trade\AgentUsersTradeLog;
use yii\db\Query;

class OrderFlowRecharge extends \common\models\database\OrderFlowRecharge {

    use OrderRechargeModelTrait;
    const order_status_unknown=0;//未知
    const order_status_recharge=1;//充值中
    const order_status_success=2;//充值成功
    const order_status_failure=3;//充值失败
    const order_status_cancel=4;//已取消

    const add_order_way_unknown=0;//未知
    const add_order_way_single_stroke=1;//单笔
    const add_order_way_batch=2;//批量

    const unit_individual=1;//个
    const unit_month=2;//月
    const unit_rmb=3;//元，人民币
    public $orderCate = AgentUsersTradeLog::ORDER_CATE_RECHARGE_FLOW;

    public function rules()
    {
        return [
            [['user_id', 'parent_dealer_user_id', 'trade_sn', 'order_sn', 'recharge_accounts', 'recharge_amount', 'product_id', 'product_name', 'isp', 'province', 'city', 'order_status', 'recharge_status', 'user_ip', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price', 'product_recharge_speed'], 'required'],
            [['user_id', 'parent_dealer_user_id', 'employee_id', 'product_id', 'order_status', 'recharge_status', 'user_ip', 'product_recharge_speed', 'add_order_way'], 'integer'],
            [['recharge_amount', 'rebate', 'unit_price', 'total_price', 'product_face_value', 'buy_num', 'user_retail_price'], 'number'],
            [['add_time', 'end_time'], 'safe'],
            ['employee_id' , 'required' , 'skipOnEmpty'=>true] ,
            ['employee_id' , 'default' , 'value' => \Yii::$app->user->getEmployeeIdIfHad()] ,
            [['trade_sn', 'product_name', 'user_info'], 'string', 'max' => 255],
            [['order_sn'], 'string', 'max' => 19],
            [['recharge_accounts'], 'string', 'max' => 11],
            [['isp'], 'string', 'max' => 2],
            [['province'], 'string', 'max' => 3],
            [['city'], 'string', 'max' => 20],
            [['order_sn'], 'unique'],
            [['trade_sn'], 'unique'],
        ];
    }

    public static $recharge_status=array('0'=>'未知','1'=>'待付款','2'=>'等待提单','3'=>'充值中','4'=>'提交失败','5'=>'充值成功',     //充值状态
        '6'=>'充值失败','7'=>'已退款','8'=>'充值可疑','9'=>'已提单','10'=>'提交可疑','11'=>'已验单','12'=>'已取消','13'=>'待处理');


    public static $unit=[1=>'个',2=>'月',3=>'元'];//充值单位





    public static function get_order_status(){
        return [
         //   self::order_status_unknown=>'未知',
            self::order_status_recharge=>'充值中',
            self::order_status_success=>'充值成功',
            self::order_status_failure=>'充值失败',
            self::order_status_cancel=>'已取消'
        ];
    }

    public static function get_add_order_way(){
        return [
          //  self::add_order_way_unknown=>'未知',
            self::add_order_way_single_stroke=>'单笔充值',
            self::add_order_way_batch=>'批量充值',
        ];
    }


    public function get_unit(){
        return [
            self::unit_individual=>'个',
            self::unit_month=>'月',
            self::unit_rmb=>'元'
        ];
    }


    public function add_order($agent_user,$product_id,$recharge_accounts,$recharge_amount,$add_order_way,$user_retail_price='',$user_info=''){

        $icp=Icp::get_mobile_icp($recharge_accounts);
        if(!$icp['status']){
            return ['status'=>false,'msg'=>'查询归属地错误'];
        }
        if(!$icp['status_icp']){
            return ['status'=>false,'msg'=>'该号码所属的运营商维护了'];
        }
        $product=(new Query())
            ->select('name,isp,province_id,area_id,face_value,can_buy_scope,arbitrarily,mvno_id,product_status,recharge_speed')
            ->from('{{%product_mobile_recharge}}')
            ->where(['id'=>$product_id])
            ->limit(1)->one();
        if(!$product){
            return ['status'=>false,'msg'=>'下单失败，商品不存在'];
        }
        if($product['product_status']!=1){
            return ['status'=>false,'msg'=>'商品状态异常'];
        }
        if($product['arbitrarily']){
            if(!Order::check_arbitrarily_can_buy_scope($product['can_buy_scope'],$product['face_value'],$recharge_amount)){
                //检测任意充金额是否符合************
                return ['status'=>false,'msg'=>'该任意充商品不支持充值该金额'];
            }
        }else if($product['face_value']!=$recharge_amount){
            return ['status'=>false,'msg'=>'商品面值与订单金额不一致'];
        }
        if($product['isp']!=$icp['isp']){
            return ['status'=>false,'msg'=>'号码运营商与商品运营商不一致'];
        }
        if($product['area_id'] && $product['area_id']!=$icp['area_id']  ){
            return ['status'=>false,'msg'=>'号码归属地区与所选商品不一致'];
        }
        //虚拟运营商的省份id总是等于1的，也就是全国，该类型的商品下单暂时不用校验省份，城市和isp
        if($product['mvno_id']!=0 && $product['province_id']!==1 && $product['province_id']!=$icp['province_id']){
            return ['status'=>false,'msg'=>'号码归属省份与所选商品不一致'];
        }

        $product_price_select='product_id,price,check_price,rebate';
        $product_price=(new Query())
            ->select($product_price_select)
            ->from('{{%product_mobile_recharge_price_user}}')
            ->where(['product_id'=>$product_id,'user_id'=>$agent_user['user_id'],'can_buy_status'=>1])
            ->limit(1)
            ->one();
        if(!$product_price){
            $product_price=(new Query())
                ->select($product_price_select)
                ->from('{{%product_mobile_recharge_price_template}}')
                ->where(['product_id'=>$product_id,'template_id'=>$agent_user['mobile_recharge_price_template_id'],'can_buy_status'=>1])
                ->limit(1)->one();
        }
        if(!$product_price){
            return ['status'=>false,'msg'=>'获取商品价格失败，如有疑问请联系客服'];
        }
        $buy_num=1;
        $unit_price=$product_price['price'];
        if($product['arbitrarily']){
            $product_price['price']=$recharge_amount*$product_price['price'];
            $buy_num=$recharge_amount;
        }
        $now=date('Y-m-d H:i:s');
        $this->order_sn=Order::build_order_sn();
        $this->product_id=$product_id;
        $this->product_name=$product['name'];
        $this->product_recharge_speed=$product['recharge_speed'];
        $this->product_face_value= $product['face_value'];

        $this->order_status=self::order_status_recharge;
        $this->recharge_status=1;
        $this->add_order_way=$add_order_way;


        $this->user_id=$agent_user['user_id'];
        $this->parent_dealer_user_id=$agent_user['parent_dealer_user_id'];

        $this->recharge_accounts=$recharge_accounts;
        $this->recharge_amount= $recharge_amount;
        $this->buy_num= $buy_num;

        $this->rebate=$product_price['rebate'];
        $this->unit_price= $unit_price;
        $this->total_price= $product_price['price'];
        $this->user_retail_price= $user_retail_price?:$recharge_amount;
        $this->user_info= $user_info;

        $this->province=$icp['province'];
        $this->city=$icp['city'];
        $this->isp=$icp['isp'];
        $this->add_time=$now;

        $this->user_ip= ip2long(\Yii::$app->request->userIP);//存ip用ip2long，取ip用long2ip()



        $add_trade_log=(new AgentUsersMoneyTradeLog());
        $add_trade_log->user_id=$this->user_id;
        $add_trade_log->name=$this->product_name;
        $add_trade_log->add_time=$now;
        $add_trade_log->pay_time=$now;
        $add_trade_log->end_time=$now;
        $add_trade_log->trade_status=AgentUsersMoneyTradeLog::trade_status_trading;
        $add_trade_log->info="支付订单[{$this->order_sn}]充值号码[{$this->recharge_accounts}]充值金额[{$this->recharge_amount}]";
        $add_trade_log->money=-abs($this->total_price);
        $this->trade_sn=$add_trade_log->trade_sn=AgentUsersMoneyTradeLog::build_trade_sn();
        $add_trade_log->order_sn=$this->order_sn;
        $add_trade_log->order_cate=Order::order_cate_mobile_recharge;
        $add_trade_log->payment_method=AgentUsersMoneyTradeLog::payment_method_balance;
        $add_trade_log->inout_type=AgentUsersMoneyTradeLog::inout_type_out;
        $add_trade_log->trade_log_type=AgentUsersMoneyTradeLog::trade_log_type_pay_order;





        if(!$this->save()){
            return ['status'=>false,'msg'=>'下单失败'];
        }

        $add_order_recharge_all=Order::add_common_order($this,Order::order_cate_mobile_recharge);

        if(!$add_order_recharge_all->save()){
            return ['status'=>false,'msg'=>'创建公共订单失败'];
        }

        if(!$add_trade_log->save()){
            return ['status'=>false,'msg'=>'创建交易记录失败'];
        }

//        $add_profit=Order::add_profit_by_order_model_when_add_order($this);
//        if(!$add_profit->save()){
//            return ['status'=>false,'msg'=>'利润记录失败','error'=>$add_profit->getFirstErrors()];
//        }

        $pay=AgentUserMoney::pay($add_trade_log);
        if(!$pay['status']){
            return $pay;
        }
        return ['status'=>true,'msg'=>'下单成功'];
    }

    public static function in_accounts_check_all_product($recharge_accounts,$agent_user){
        $face_value=[10,20,30,50,100,200,300,500];
        $icp=Icp::get_mobile_icp($recharge_accounts);
        if(!$icp['status']){
            return ['status'=>false,'msg'=>'查询归属地错误'];
        }
        if(!$icp['status_icp']){
            return ['status'=>false,'msg'=>'该号码所属的运营商维护了'];
        }
        $data=[];
        foreach ($face_value as $k=>$v){
            $data[$v]['status']=false;
        }

        $where=['or',['province_id'=>$icp['province_id'],'area_id'=>0],['province_id'=>$icp['province_id'],'area_id'=>$icp['area_id']],['province_id'=>1,'area_id'=>0]];
        $product=(new Query())->select('id,face_value,arbitrarily,can_buy_scope')
            ->from('{{%product_mobile_recharge}}')
            ->where($where)
            ->andWhere(['product_status'=>1,'face_value'=>$face_value,'isp'=>$icp['isp'],'mvno_id'=>$icp['mvno_id']])
            ->all();
        if(!$product){
            return ['status'=>true,'product'=>$data,'msg'=>'抱歉！系统暂不支持充值该号码，如果有疑问可联系客服'];
        }
        $product_ids=array_column($product,'id');
        $price_template=(new Query())->select('product_id')
            ->from('{{%product_mobile_recharge_price_template}}')
            ->where(['product_id'=>$product_ids,'can_buy_status'=>1,'template_id'=>$agent_user['mobile_recharge_price_template_id']])
            ->column();
        $price_user=(new Query())->select('product_id')
            ->from('{{%product_mobile_recharge_price_user}}')
            ->where(['product_id'=>$price_template,'can_buy_status'=>1,'user_id'=>$agent_user['user_id']])
            ->column();
        $product_price_ids=array_unique(array_merge($price_template,$price_user));
        $can_recharge=false;
        foreach ($product as $k=>$v){
            if(in_array($v['id'],$product_price_ids)){
                $can_recharge=true;
                $data[intval($v['face_value'])]['status']=true;
            }
        }
        if($can_recharge){
            return ['status'=>true,'product'=>$data,'msg'=>'该号码可以充值，请选择要充值的金额'];
        }
        return ['status'=>true,'product'=>$data,'msg'=>'抱歉！系统找不到支持充值该面值的商品',$product,$product_price_ids];
    }

    public static function face_vlue_check($recharge_accounts,$agent_user,$recharge_amount,$recharge_speed=1){
        $icp=Icp::get_mobile_icp($recharge_accounts);
        if(!$icp['status']){
            return ['status'=>false,'msg'=>'查询归属地错误'];
        }
        if(!$icp['status_icp']){
            return ['status'=>false,'msg'=>'该号码所属的运营商维护了'];
        }

        $select='p.can_buy_scope,p.arbitrarily,p.province_id,p.area_id,p.isp,p.recharge_speed,p.id,p.name,p_t.product_id,p_t.price,p.face_value as recharge_amount,p_t.check_price,p_t.can_buy_status,';
        //*****************//省份地区的*********************************************************//省份+城市地区的*****************************************************//全国的
        $where=['or',['p.province_id'=>$icp['province_id'],'p.area_id'=>0],['p.province_id'=>$icp['province_id'],'p.area_id'=>$icp['area_id']],['p.province_id'=>1,'p.area_id'=>0]];
        $query=(new \yii\db\Query()) ->from('{{%product_mobile_recharge}} as p')
            ->where(['or',['and',['p.arbitrarily'=>1],$where],['and',['p.face_value'=>$recharge_amount],$where]])
            ->andWhere(['p.isp'=>$icp['isp'],'p.mvno_id'=>$icp['mvno_id'],'p.recharge_speed'=>$recharge_speed,'p.product_status'=>1]);//商品状态为在售
        $product_u=(clone $query)->select($select) ->join('INNER JOIN','{{%product_mobile_recharge_price_user}} as p_t','p.id=p_t.product_id')->andWhere(['p_t.user_id'=>$agent_user['user_id']]);
        $product_t=$query->select($select) ->join('INNER JOIN','{{%product_mobile_recharge_price_template}} as p_t','p.id=p_t.product_id') ->andWhere(['p_t.template_id'=>$agent_user['mobile_recharge_price_template_id']])
            ->andWhere('not exists(select * from {{%product_mobile_recharge_price_user}} as p_u where p_u.user_id='.$agent_user['user_id'].' and p_u.product_id=p_t.product_id)');
        $product=$product_u->union($product_t)->all();
        if(!$product){
            return ['status'=>false,'msg'=>'抱歉！找不到可以充值该面值的商品'];
        }
        $data=[];
        foreach ($product as $k=>$v){
            $data[$k]['product_id']=$v['product_id'];
            $data[$k]['status']=$v['can_buy_status'];
            $data[$k]['renyi_type']=$v['arbitrarily'];
            $data[$k]['name']=$v['name'];
            $data[$k]['sell_price']=$v['price'];
        }
        $product['recharge_amount']=$recharge_amount;
        $product['status']=true;
        $product['products']=$data;
        return $product;
    }
}