<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-03-09
 * Time: 21:31
 */

namespace agent_models\business\product\recharge;
class ProductTencentRecharge extends \agent_models\database\ProductTencentRecharge
{

    const PRODUCT_STATUS_OFF = 0;//维护
    const PRODUCT_STATUS_ON  = 1;//正常

    const TENCENT_CATE_QB              = 1;
    const TENCENT_CATE_QQPUTONGTUIYUAN = 2;
    const TENCENT_CATE_QQCHAOJIHUIYUAN = 3;


    const TENCENT_CATE_HUANGZUAN = 4;
    const TENCENT_CATE_LVZUAN    = 5;
    const TENCENT_CATE_FENZUAN   = 6;


    const UNIT_INDIVIDUAL = 1;//个
    const UNIT_MONTH      = 2;//月
    const UNIT_RMB        = 3;//元，人民币

    const PRODUCT_STATUSES
        = [
            self::PRODUCT_STATUS_OFF => '维护' ,
            self::PRODUCT_STATUS_ON  => '正常' ,
        ];

    const UNITS=[
        self::UNIT_INDIVIDUAL => '个' ,
        self::UNIT_MONTH      => '月' ,
        self::UNIT_RMB        => '元',
    ];

    public static function getTencentCates()
    {

        return [
            self::TENCENT_CATE_QB              => ['name' => 'Q币充值' , 'unit' => self::UNIT_INDIVIDUAL , 'can_buy_scope' => '1-1000' , 'product_id' => 10000 , 'face_value' => 1] ,
            self::TENCENT_CATE_QQPUTONGTUIYUAN => ['name' => 'QQ普通会员' , 'unit' => self::UNIT_MONTH , 'can_buy_scope' => '1-1000' , 'product_id' => 20000 , 'face_value' => 10] ,
            self::TENCENT_CATE_QQCHAOJIHUIYUAN => ['name' => 'QQ超级会员' , 'unit' => self::UNIT_MONTH , 'can_buy_scope' => '1-1000' , 'product_id' => 30000 , 'face_value' => 20] ,
            self::TENCENT_CATE_HUANGZUAN       => ['name' => '黄钻' , 'unit' => self::UNIT_MONTH , 'can_buy_scope' => '1-1000' , 'product_id' => 40000 , 'face_value' => 10] ,
            self::TENCENT_CATE_LVZUAN          => ['name' => '绿钻' , 'unit' => self::UNIT_MONTH , 'can_buy_scope' => '1-1000' , 'product_id' => 50000 , 'face_value' => 10] ,
            self::TENCENT_CATE_FENZUAN         => ['name' => '粉钻' , 'unit' => self::UNIT_MONTH , 'can_buy_scope' => '1-1000' , 'product_id' => 60000 , 'face_value' => 10] ,
        ];
    }

    public static function getTencentProducts()
    {
        $cate    = self::getTencentCates();
        $product = [];
        foreach ($cate as $k => $v) {
            $product[$v['product_id']]                 = $v;
            $product[$v['product_id']]['tencent_cate'] = $k;
        }

        $product = self::find()->asArray()->all();
        return $product;
    }


    public static function checkArbitrarilyCanBuyScope($can_buy_scope,$face_value,$buy_num){
        $limits=explode(',',$can_buy_scope);
        foreach ($limits as $v){
            if(is_numeric($v)){
                if($buy_num==$v){
                    return true;
                }
            }else{
                $limit=explode('-',$v);
                if(count($limit)==1){
                    if(is_numeric($limit[0]) && $buy_num==($limit[0])){//左边范围*面值=
                        return true;
                    }
                }elseif(count($limit)==2){
                    asort($limit);
                    if(is_numeric($limit[0]) && is_numeric($limit[1])){
                        if($buy_num >= ($limit[0]) && $buy_num<=($limit[1])){
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

}