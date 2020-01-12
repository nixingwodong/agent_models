<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-03-24
 * Time: 14:38
 */
namespace agent_models\business\finance\rebate;
use agent_models\business\order\recharge\OrderRechargeAll;

class AgentUsersRebateBalance extends AgentUsers{


    public static function addRebate(OrderRechargeAll $order,$rebate_log_type,$info){
        $refund_money=$order->rebate;
        $update_rebate=self::userRebateBalanceUpdate($order->parent_dealer_user_id,$refund_money);
        if(!$update_rebate['status']){
            return $update_rebate;
        }
        $add_rebate_log=(new AgentUsersRebateLog())->add_rebate_log_by_order($order,$rebate_log_type,$info,$update_rebate['after_balance']);
        if(!$add_rebate_log['status']){
            return $add_rebate_log;
        }
        return ['status'=>true,'msg'=>'退款成功'];
    }



    //退款佣金
    public static function refund_rebate(OrderRechargeAll $order,$rebate_log_type,$info,$refund_money){
        $refund_money=-abs($refund_money);
        $update_rebate=self::userRebateBalanceUpdate($order->parent_dealer_user_id,$refund_money);
        if(!$update_rebate['status']){
            return $update_rebate;
        }
        $add_rebate_log=(new AgentUsersRebateLog())->add_rebate_log_for_refund($order,$rebate_log_type,$info,$refund_money,$update_rebate['after_balance']);
        if(!$add_rebate_log['status']){
            return $add_rebate_log;
        }
        return ['status'=>true,'msg'=>'退款成功'];
    }

    public static function userRebateBalanceUpdate($user_id,$rebate){

        $t=\Yii::$app->db->beginTransaction();
        $user_rebate=\Yii::$app->db->createCommand("SELECT rebate_balance FROM ".self::tableName()." WHERE user_id=".intval($user_id)." for update")->queryOne();
        if(!$user_rebate){
            return ['status'=>false,'msg'=>"操作相应用户余额发生错误（非本平台用户）"];
        }
        $user_rebate=$user_rebate['rebate_balance'];
        //如果rebate小于0则扣款
        if($rebate < 0){
            $abs_rebate=abs($rebate);
            if($user_rebate < $abs_rebate){//$rebate为负数,要转换成正数才能比较大小
                $t->rollBack();
                return ['status'=>false,'msg'=>"账户余额{$user_rebate}元，不足以扣款{$abs_rebate}元。"];//扣款失败-账户余额不足
            }
            $updata=self::updateAllCounters(['rebate_balance'=>$rebate],['and',['user_id'=>$user_id],['>=','rebate_balance',$abs_rebate]]);
            if(!$updata){
                $t->rollBack();
                return ['status'=>false,'msg'=>'扣款失败，系统异常'];//扣款失败
            }
        }elseif($rebate > 0){//如果rebate大于0则加款
            $updata=self::updateAllCounters(['rebate_balance'=>$rebate],['user_id'=>$user_id]);
            if(!$updata){
                $t->rollBack();
                return ['status'=>false,'msg'=>'加款失败，系统异常'];//充值余额失败
            }
        }
        $t->commit();
        return ['status'=>true,'after_balance'=>$user_rebate+$rebate,'platform_after_balance'=>self::find()->sum('rebate_balance')];
    }

}