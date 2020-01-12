<?php


namespace common\models\business\printing;


class AgentUsersPrintSettings extends \common\models\database\AgentUsersPrintSettings
{

    const PRINT_TYPE_58MM     = 0;
    const PRINT_TYPE_ORDINARY = 1;


    public function rules()
    {
        return [
            [['user_id' , 'view' , 'content'] , 'required'] ,
            [['user_id' , 'print_type'] , 'integer'] ,
            [['view'] , 'string' , 'max' => 500] ,
            [['content'] , 'string' , 'max' => 1000] ,
            [['user_id'] , 'unique'] ,
            [['print_type'] , 'in' , 'range' => [self::PRINT_TYPE_58MM , self::PRINT_TYPE_ORDINARY]] ,
        ];
    }

    public static function getPrintContentFieldDefault()
    {
        return [
            "title_content"          => "充值回执单" ,
            "contact_mobile_content" => \Yii::$app->user->identity->username ,
            "shop_address_content"   => \Yii::$app->user->agentUser->shop_address ,
            "shop_name_content"      => \Yii::$app->user->agentUser->shop_name ,
            "foot_content"           => "感谢您的光临，欢迎下次惠顾" ,
            "product_type_content"   => "充值缴费" ,
            "print_info_content"     => "缴纳费用的凭证，不能作为报帐凭证" ,
        ];
    }

    public static function getPrintViewDefault()
    {
        return [
            'recharge_amount' => 1 ,
            'order_sn'        => 1 ,
            'product_name'    => 1 ,
            'on'              => 1 ,
            'on_before_info'  => 0 ,
            'pay_money'       => 1 ,
            'add_time'        => 1 ,
            'contact_mobile'  => 1 ,
            'shop_address'    => 1 ,
            'shop_name'       => 1 ,
            'title'           => 1 ,
            'cashier'         => 1 ,
            'foot'            => 1 ,
        ];


    }
}