<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-03-21
 * Time: 23:21
 */

namespace agent_models\business\product\recharge;
class ProductGameRecharge
{

    const GAME_PLATFORM_TENCENT        = 1;
    const GAME_PLATFORM_SHENGDA        = 2;
    const GAME_PLATFORM_WANGYI         = 3;
    const GAME_PLATFORM_SHIJITIANCHENG = 4;

    const GAME_PLATFORM_JUNKA        = 5;
    const GAME_PLATFORM_WANMEISHIJIE = 6;
    const GAME_PLATFORM_YOUXIWONIU   = 7;
    const GAME_PLATFORM_JUREN        = 8;
    const GAME_PLATFORM_DIANHUN      = 9;
    const GAME_PLATFORM_GUANGYU      = 10;

    const GAME_ID_DIXIACHENG = 1;


    const GAME_RECHARGE_OTHER_INFO_VALUE_TYPE_SELECT = 1;//下拉框选择值，此时必须设置value_scope
    const GAME_RECHARGE_OTHER_INFO_VALUE_TYPE_TEXT   = 2;//文本框输入值

    public static function getNamePlatform()
    {
        return [
            self::GAME_PLATFORM_TENCENT        => ['name' => '腾讯游戏' , 'img_url' => '//oss.foxdou.com/agent/img/img-5.jpg' , 'platform' => self::GAME_PLATFORM_TENCENT] ,
            self::GAME_PLATFORM_SHENGDA        => ['name' => '盛大游戏' , 'img_url' => '//oss.foxdou.com/agent/img/img-1.jpg' , 'platform' => self::GAME_PLATFORM_SHENGDA] ,
            self::GAME_PLATFORM_WANGYI         => ['name' => '网易游戏' , 'img_url' => '//oss.foxdou.com/agent/img/img-3.jpg' , 'platform' => self::GAME_PLATFORM_WANGYI] ,
            self::GAME_PLATFORM_SHIJITIANCHENG => ['name' => '世纪天成' , 'img_url' => '//oss.foxdou.com/agent/img/img-2.jpg' , 'platform' => self::GAME_PLATFORM_SHIJITIANCHENG] ,
            self::GAME_PLATFORM_JUNKA          => ['name' => '骏卡' , 'img_url' => '//oss.foxdou.com/agent/img/img-4.jpg' , 'platform' => self::GAME_PLATFORM_JUNKA] ,
            self::GAME_PLATFORM_WANMEISHIJIE   => ['name' => '完美世界' , 'img_url' => '//oss.foxdou.com/agent/img/img-6.jpg' , 'platform' => self::GAME_PLATFORM_WANMEISHIJIE] ,
            self::GAME_PLATFORM_YOUXIWONIU     => ['name' => '游戏蜗牛' , 'img_url' => '//oss.foxdou.com/agent/img/img-7.jpg' , 'platform' => self::GAME_PLATFORM_YOUXIWONIU] ,
            self::GAME_PLATFORM_JUREN          => ['name' => '巨人网络' , 'img_url' => '//oss.foxdou.com/agent/img/img-8.jpg' , 'platform' => self::GAME_PLATFORM_JUREN] ,
            self::GAME_PLATFORM_DIANHUN        => ['name' => '电魂' , 'img_url' => '//oss.foxdou.com/agent/img/img-9.jpg' , 'platform' => self::GAME_PLATFORM_DIANHUN] ,
            self::GAME_PLATFORM_GUANGYU        => ['name' => '光宇游戏' , 'img_url' => '//oss.foxdou.com/agent/img/img-10.jpg' , 'platform' => self::GAME_PLATFORM_GUANGYU] ,
        ];
    }


    public static function getName()
    {
        return [
            self::GAME_ID_DIXIACHENG => [
                'id'                  => self::GAME_ID_DIXIACHENG , 'name' => '地下城与勇士' , 'platform' => self::GAME_PLATFORM_TENCENT , 'img_url' => '' , 'unit' => '' ,
                'recharge_other_info' => [
                    ['name'        => '游戏区服' , 'field' => 'game_zone' , 'value_type' => self::GAME_RECHARGE_OTHER_INFO_VALUE_TYPE_SELECT ,
                     'value_scope' =>
                         [
                             1 => ['value' => 1 , 'name' => '电信区服1'] ,//键key，要与value值保持一致
                             2 => ['value' => 2 , 'name' => '电信区服2'] ,
                         ] ,
                    ] ,
                    ['name' => '盛大通行证' , 'field' => 'game_on' , 'value_type' => self::GAME_RECHARGE_OTHER_INFO_VALUE_TYPE_TEXT] ,
                    ['name' => '邮箱' , 'field' => 'game_mail' , 'value_type' => self::GAME_RECHARGE_OTHER_INFO_VALUE_TYPE_TEXT] ,
                ] ,
            ] ,
        ];
    }

    public static function getNameByGameId($game_id)
    {
        return self::getName()[$game_id];
    }


    //获取点卡游戏商品
    public static function getNameProductPointCard($game_id , $recharge_amount)
    {
        $game = self::getName()[$game_id];

        foreach ($game['product_point_card'] as $k => $v) {
            if ($v['face_value'] == $recharge_amount) {

            }
        }

    }

    public static function checkArbitrarilyCanBuyScope($can_buy_scope , $face_value , $recharge_amount)
    {
        $limits = explode(',' , $can_buy_scope);

        foreach ($limits as $v) {
            if (is_numeric($v)) {//指定的某个数
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

}