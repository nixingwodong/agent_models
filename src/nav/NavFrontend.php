<?php

namespace agent_models\nav;

use agent_models\User;
use agent_models\permission\PowerList;
use yii\helpers\Url;

class NavFrontend
{
    const NAV_ID_HOME                = 100;
    const NAV_ID_HOME_DASHBOARD      = 101;
    const NAV_ID_HOME_ACCOUNTS       = 102;
    const NAV_ID_HOME_PRINT_SETTING  = 103;
    const NAV_ID_HOME_EMPLOYEE       = 104;
    const NAV_ID_HOME_EMPLOYEE_LIST  = 105;
    const NAV_ID_HOME_EMPLOYEE_POST  = 106;
    const NAV_ID_HOME_OPERLATION_LOG = 107;
    const NAV_ID_HOME_SAFE           = 108;
    const NAV_ID_HOME_NOTICE         = 109;


    const NAV_ID_BUY                    = 200;
    const NAV_ID_BUY_MOBILE_RECHARGE    = 201;
    const NAV_ID_BUY_TELEPHONE_RECHARGE = 202;
    const NAV_ID_BUY_TENCENT_RECHARGE   = 203;


    const NAV_ID_ORDER                    = 300;
    const NAV_ID_ORDER_ALL                = 301;
    const NAV_ID_ORDER_MOBILE_RECHARGE    = 302;
    const NAV_ID_ORDER_TELEPHONE_RECHARGE = 303;
    const NAV_ID_ORDER_TENCENT_RECHARGE   = 304;


    const NAV_ID_FINANCE                       = 400;
    const NAV_ID_FINANCE_MYFINANCE             = 401;
    const NAV_ID_FINANCE_REBATE_CASH           = 402;
    const NAV_ID_FINANCE_TRANSFER_ACCOUNTS     = 403;
    const NAV_ID_FINANCE_ACCOUNTS_DETAILS      = 404;
    const NAV_ID_FINANCE_TRADE_LOG             = 405;
    const NAV_ID_FINANCE_RECHARGE_LOG          = 406;
    const NAV_ID_FINANCE_REBATE_LOG            = 407;
    const NAV_ID_FINANCE_MONEY_LOG             = 408;
    const NAV_ID_FINANCE_TRANSFER_ACCOUNTS_LOG = 409;
    const NAV_ID_FINANCE_RECHARGE              = 410;


    const NAV_ID_PRODUCT                    = 500;
    const NAV_ID_PRODUCT_MOBILE_RECHARGE    = 501;
    const NAV_ID_PRODUCT_TELEPHONE_RECHARGE = 502;
    const NAV_ID_PRODUCT_TENCENT_RECHARGE   = 503;


    const NAV_ID_MARKETING             = 600;
    const NAV_ID_MARKETING_SPREAD_URL  = 601;
    const NAV_ID_MARKETING_SPREAD_USER = 602;


    public static function getAllNav()
    {
        return [

            self::NAV_ID_HOME => [
                'title'    => '控制面板' , 'power_id' => PowerList::POWER_ID_HOME , 'url' => Url::to(['/home/default/index']) ,
                'left_nav' =>
                    [
                        self::NAV_ID_HOME_DASHBOARD      => ['title' => '控制面板' , 'icon' => '&#xe62b;' , 'power_id' => PowerList::POWER_ID_HOME_DASHBORD , 'url' => Url::to(['/home/dashboard/index']) ,] ,
                        self::NAV_ID_HOME_ACCOUNTS       => ['title' => '账户信息' , 'icon' => '&#xe61b;' , 'power_id' => PowerList::POWER_ID_HOME_ACCOUNTS , 'url' => Url::to(['/home/accounts/index']) ,] ,
                        self::NAV_ID_HOME_PRINT_SETTING  => ['title' => '打印设置' , 'icon' => '&#xe62c;' , 'power_id' => PowerList::POWER_ID_HOME_PRINT_SETTING , 'url' => Url::to(['/home/print-settings/index']) ,] ,
                        self::NAV_ID_HOME_EMPLOYEE       => [
                            'title'          => '员工管理' , 'role' => [User::ROLE_ID] , 'icon' => '&#xe61f;' , 'url' => Url::to(['/home/employee/list']) ,'not_power_url'=>Url::to(['/home/employee/index']),
                            'right_head_nav' => [
                                self::NAV_ID_HOME_EMPLOYEE_LIST => ['title' => '员工列表' , 'icon' => '&#xe643;' , 'url' => Url::to(['/home/employee/list']) ,] ,
                                self::NAV_ID_HOME_EMPLOYEE_POST => ['title' => '岗位管理' , 'icon' => '&#xe643;' , 'url' => Url::to(['/home/employee/post']) ,] ,
                            ] ,
                        ] ,
                        self::NAV_ID_HOME_OPERLATION_LOG => ['title' => '操作日志' , 'icon' => '&#xe633;' , 'power_id' => PowerList::POWER_ID_HOME_OPLATION_LOG , 'url' => Url::to(['/home/operation-log/index']) ,] ,
                        self::NAV_ID_HOME_SAFE           => ['title' => '安全中心' , 'icon' => '&#xe6c7;' , 'power_id' => PowerList::POWER_ID_HOME_SAFE , 'url' => Url::to(['/home/safe/index']) ,] ,
                        self::NAV_ID_HOME_NOTICE         => ['title' => '平台公告' , 'icon' => '&#xe654;' , 'power_id' => PowerList::POWER_ID_HOME_NOTICE , 'url' => Url::to(['/home/notice/index']) ,] ,
                        //                        ['title' => '安全策略' , 'icon' => '&#xe633;' , 'power_id' =>  , 'url' => Url::to(['/home/safe-strategy/index']) ,] ,
                    ] ,
            ] ,

            self::NAV_ID_BUY => [
                'title'    => '我要充值' , 'power_id' => PowerList::POWER_ID_BUY , 'url' => Url::to(['/buy/default/index']) ,
                'left_nav' =>
                    [
                        self::NAV_ID_BUY_MOBILE_RECHARGE    => ['title' => '手机话费充值' , 'icon' => '&#xe608;' , 'power_id' => PowerList::POWER_ID_BUY_MOBILE_RECHARGE , 'url' => Url::to(['/buy/mobile-recharge/index']) ,] ,
                        self::NAV_ID_BUY_TELEPHONE_RECHARGE => ['title' => '固话宽带充值' , 'icon' => '&#xe600;' , 'power_id' => PowerList::POWER_ID_BUY_TELEPHONE_RECHARGE , 'url' => Url::to(['/buy/telephone-recharge/index']) ,] ,
                        self::NAV_ID_BUY_TENCENT_RECHARGE   => ['title' => '腾讯业务充值' , 'icon' => '&#xe60b;' , 'power_id' => PowerList::POWER_ID_BUY_TENCENT_RECHARGE , 'url' => Url::to(['/buy/tencent-recharge/index']) ,] ,
                        //                        ['title' => '手机流量充值' , 'icon' => '&#xe612;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '游戏点卡充值' , 'icon' => '&#xe61e;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '充值卡密购买' , 'icon' => '&#xe617;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '水电燃气缴费' , 'icon' => '&#xe683;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '汽车加油充值' , 'icon' => '&#xe615;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '交通违章缴纳' , 'icon' => '&#xe60d;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '飞机票务预定' , 'icon' => '&#xe610;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '汽车票务预定' , 'icon' => '&#xe715;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '火车票务预定' , 'icon' => '&#xe628;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '电影票务预定' , 'icon' => '&#xe611;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                        //                        ['title' => '医院挂号预约' , 'icon' => '&#xe64a;' , 'power_id' => '@' , 'url' => 'javascrpt:void(0);' ,] ,
                    ] ,
            ] ,

            self::NAV_ID_ORDER => [
                'title'    => '我的订单' , 'power_id' => PowerList::POWER_ID_ORDER , 'url' => Url::to(['/order/default/index']) ,
                'left_nav' =>
                    [
                        self::NAV_ID_ORDER_ALL                => ['title' => '全部订单列表' , 'icon' => '&#xe683;' , 'power_id' => PowerList::POWER_ID_ORDER_ALL , 'url' => Url::to(['/order/recharge-all/index']) ,] ,
                        self::NAV_ID_ORDER_MOBILE_RECHARGE    => ['title' => '手机话费订单' , 'icon' => '&#xe608;' , 'power_id' => PowerList::POWER_ID_ORDER_MOBILE_RECHARGE , 'url' => Url::to(['/order/mobile-recharge/index']) ,] ,
                        self::NAV_ID_ORDER_TELEPHONE_RECHARGE => ['title' => '固话宽带订单' , 'icon' => '&#xe600;' , 'power_id' => PowerList::POWER_ID_ORDER_TELEPHONE_RECHARGE , 'url' => Url::to(['/order/telephone-recharge/index']) ,] ,
                        self::NAV_ID_ORDER_TENCENT_RECHARGE   => ['title' => '腾讯业务订单' , 'icon' => '&#xe60b;' , 'power_id' => PowerList::POWER_ID_ORDER_TENCENT_RECHARGE , 'url' => Url::to(['/order/tencent-recharge/index']) ,] ,
                        //  ['title' => '手机流量充值' , 'icon' => '&#xe612;' , 'power_id' => 'order/flow-recharge/index' , 'url' => Url::to(['/order/flow-recharge/index']) ,] ,

                        //     ['title' => '游戏点卡充值' , 'icon' => '&#xe61e;' , 'power_id' => 'order/game-recharge/index' , 'url' => Url::to(['/order/game-recharge/index']) ,] ,
                        /*          ['title'=>'充值卡密购买','icon'=>'&#xe617;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'水电燃气缴费','icon'=>'&#xe683;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'汽车加油充值','icon'=>'&#xe615;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'交通违章缴纳','icon'=>'&#xe60d;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'飞机票务预定','icon'=>'&#xe610;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'汽车票务预定','icon'=>'&#xe715;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'火车票务预定','icon'=>'&#xe628;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'电影票务预定','icon'=>'&#xe611;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'医院挂号预约','icon'=>'&#xe64a;','power_id'=>'@','url'=>'javascrpt:void(0);',],*/
                    ] ,
            ] ,

            self::NAV_ID_FINANCE => [
                'title'    => '我的财务' , 'power_id' => PowerList::POWER_ID_FINANCE , 'url' => Url::to(['/finance/default/index']) ,
                'left_nav' => [
                    self::NAV_ID_FINANCE_MYFINANCE         =>
                        ['title'          => '我的财务' , 'icon' => '&#xe643;' , 'power_id' => PowerList::POWER_ID_FINANCE_ACCOUNTS_DETAILS , 'url' => Url::to(['/finance/accounts-details/index']) ,'not_power_url'=>Url::to(['/finance/default/index'])  ,
                         'right_head_nav' =>
                             [
                                 self::NAV_ID_FINANCE_ACCOUNTS_DETAILS => ['title' => '账户概览' , 'power_id' => PowerList::POWER_ID_FINANCE_ACCOUNTS_DETAILS , 'url' => Url::to(['/finance/accounts-details/index']) ,] ,
                                 self::NAV_ID_FINANCE_TRADE_LOG        => ['title' => '交易记录' , 'power_id' => PowerList::POWER_ID_FINANCE_TRADE_LOG , 'url' => Url::to(['/finance/trade-log/index']) ,] ,
                                 self::NAV_ID_FINANCE_MONEY_LOG        => ['title' => '资金明细' , 'power_id' => PowerList::POWER_ID_FINANCE_MONEY_LOG , 'url' => Url::to(['/finance/money-log/index']) ,] ,
                                 self::NAV_ID_FINANCE_RECHARGE_LOG     => ['title' => '充值记录' , 'power_id' => PowerList::POWER_ID_FINANCE_RECHARGE_LOG , 'url' => Url::to(['/finance/recharge-log/index']) ,] ,
                                 self::NAV_ID_FINANCE_REBATE_LOG       => ['title' => '佣金明细' , 'power_id' => PowerList::POWER_ID_FINANCE_REBATE_LOG , 'url' => Url::to(['/finance/rebate-log/index']) ,] ,
                             ] ,
                         'child_nav'      =>
                             [
                                 self::NAV_ID_FINANCE_RECHARGE => ['title' => '余额充值' , 'power_id' => PowerList::POWER_ID_FINANCE_RECHARGE , 'url' => Url::to(['/finance/balance-recharge/index']) ,] ,
                             ] ,
                        ] ,
                    self::NAV_ID_FINANCE_REBATE_CASH       => ['id' => self::NAV_ID_FINANCE_REBATE_CASH , 'title' => '提取佣金' , 'icon' => '&#xe631;' , 'power_id' => PowerList::POWER_ID_FINANCE_REBATE_CASH , 'url' => Url::to(['/finance/rebate-cash/index']) ,] ,
                    self::NAV_ID_FINANCE_TRANSFER_ACCOUNTS => ['id' => self::NAV_ID_FINANCE_TRANSFER_ACCOUNTS , 'title' => '我要转账' , 'icon' => '&#xe8fb;' , 'power_id' => PowerList::POWER_ID_FINANCE_TRANSFER_ACCOUNTS , 'url' => Url::to(['/finance/transfer-accounts/index']) ,] ,
                ] ,

            ] ,

            self::NAV_ID_PRODUCT => [
                'title'    => '我的商品' , 'power_id' => PowerList::POWER_ID_PRODUCT , 'url' => Url::to(['/product/default/index']) ,
                'left_nav' =>
                    [
                        self::NAV_ID_PRODUCT_MOBILE_RECHARGE    => ['title' => '手机话费商品' , 'icon' => '&#xe608;' , 'power_id' => PowerList::POWER_ID_PRODUCT_MOBILE_RECHARGE , 'url' => Url::to(['/product/mobile-recharge/index']) ,] ,
                        self::NAV_ID_PRODUCT_TELEPHONE_RECHARGE => ['title' => '固话宽带商品' , 'icon' => '&#xe600;' , 'power_id' => PowerList::POWER_ID_PRODUCT_TELEPHONE_RECHARGE , 'url' => Url::to(['/product/telephone-recharge/index']) ,] ,
                        self::NAV_ID_PRODUCT_TENCENT_RECHARGE   => ['title' => '腾讯业务商品' , 'icon' => '&#xe60b;' , 'power_id' => PowerList::POWER_ID_PRODUCT_TENCENT_RECHARGE , 'url' => Url::to(['/product/tencent-recharge/index']) ,] ,
                        //     ['title' => '手机流量充值' , 'icon' => '&#xe612;' , 'power_id' => 'product/flow-recharge/index' , 'url' => Url::to(['/product/flow-recharge/index']) ,] ,

                        //     ['title' => '游戏点卡充值' , 'icon' => '&#xe61e;' , 'power_id' => 'product/game-recharge/index' , 'url' => Url::to(['/product/game-recharge/index']) ,] ,
                        /*          ['title'=>'充值卡密购买','icon'=>'&#xe617;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'水电燃气缴费','icon'=>'&#xe683;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'汽车加油充值','icon'=>'&#xe615;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'交通违章缴纳','icon'=>'&#xe60d;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'飞机票务预定','icon'=>'&#xe610;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'汽车票务预定','icon'=>'&#xe715;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'火车票务预定','icon'=>'&#xe628;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'电影票务预定','icon'=>'&#xe611;','power_id'=>'@','url'=>'javascrpt:void(0);',],
                                    ['title'=>'医院挂号预约','icon'=>'&#xe64a;','power_id'=>'@','url'=>'javascrpt:void(0);',],*/
                    ] ,
            ] ,

            self::NAV_ID_MARKETING => [
                'title'    => '我的推广' , 'power_id' => PowerList::POWER_ID_MARKETING , 'url' => Url::to(['/marketing/default/index']) ,
                'left_nav' =>
                    [
                        self::NAV_ID_MARKETING_SPREAD_URL  => ['title' => '推广链接' , 'icon' => '&#xe64e;' , 'power_id' => PowerList::POWER_ID_MARKETING_SPREAD_URL , 'url' => Url::to(['/marketing/spread-url/index'])] ,
                        self::NAV_ID_MARKETING_SPREAD_USER => ['title' => '我的用户' , 'icon' => '&#xe64f;' , 'power_id' => PowerList::POWER_ID_MARKETING_SPREAD_USER , 'url' => Url::to(['/marketing/spread-user/index'])] ,
                    ] ,
            ] ,


        ];
    }

    public static function getHeadNav()
    {
        return self::checkPermistion(self::getAllNav());
    }

    public static function getLeftNav($headNavId)
    {
        return self::checkPermistion(self::getAllNav()[$headNavId]['left_nav']);
    }

    public static function getRightHeadNav($headNavId , $parentNavId)
    {
        return self::checkPermistion(self::getAllNav()[$headNavId]['left_nav'][$parentNavId]['right_head_nav']);
    }

    public static function getChildNav($headNavId , $parentNavId)
    {
        $allNav = self::getAllNav();;
        return isset($allNav[$headNavId]['left_nav'][$parentNavId]['child_nav']) ? self::checkPermistion($allNav[$headNavId]['left_nav'][$parentNavId]['child_nav']) : [];
    }

    /**
     * @param $nav
     * @return array
     */
    private static function checkPermistion($nav)
    {
        foreach ($nav as $k => $v) {
            $nav[$k]['id'] = $k;
            if (!empty($v['power_id']) && !\Yii::$app->user->permission->isHadPowerForLoginUserByPowerId($v['power_id'])) {
                if(empty($v['not_power_url'])){
                    unset($nav[$k]);
                }else{
                  $nav[$k]['url']= $v['not_power_url'];
                }
            }
            if (!empty($v['role']) && !in_array(\Yii::$app->user->roleId , $v['role'])) {
                unset($nav[$k]);
            }
        }
        return array_values($nav);
    }

}