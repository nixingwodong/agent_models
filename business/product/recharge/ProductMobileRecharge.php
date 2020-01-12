<?php


namespace common\models\business\product\recharge;
use common\models\business\order\recharge\Common;
use common\models\User;
use nixingwodong\cf_all\yii_business\Icp;
use yii\db\Query;

class ProductMobileRecharge extends \common\models\database\ProductMobileRecharge
{

    const PRODUCT_STATUS_OFF = 0;//维护
    const PRODUCT_STATUS_ON  = 1;//正常

    const RECHARGE_SPEED_FAST = 1;
    const RECHARGE_SPEED_SLOW = 2;

    const ISP_NAME_MNO     = '移动';
    const ISP_NAME_UNICOM  = '联通';
    const ISP_NAME_TELECOM = '电信';
    const ISP_NAME_MVNO    = '虚商';

    const ISPS
        = [
            self::ISP_NAME_MNO     => '移动' ,
            self::ISP_NAME_UNICOM  => '联通' ,
            self::ISP_NAME_TELECOM => '电信' ,

            self::ISP_NAME_MVNO    => '虚商' ,
        ];

    const PRODUCT_STATUSES
        = [
            self::PRODUCT_STATUS_OFF => '维护' ,
            self::PRODUCT_STATUS_ON  => '正常' ,
        ];

    const RECHARGE_SPEEDS
        = [
            self::RECHARGE_SPEED_FAST => '快充' ,
            self::RECHARGE_SPEED_SLOW => '慢充' ,
        ];


}