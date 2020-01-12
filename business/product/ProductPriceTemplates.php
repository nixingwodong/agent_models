<?php

namespace common\models\business\product;

use common\models\business\trade\AgentUsersTradeLog;
use common\models\traits\YiiModelTrait;

class ProductPriceTemplates extends \common\models\database\ProductPriceTemplates
{
    use YiiModelTrait;
    const STATUS_OFF = 0;
    const STATUS_ON  = 1;

    const STATUSES
        = [
            self::STATUS_OFF => '已停用' ,
            self::STATUS_ON  => '已启用' ,
        ];


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name' , 'remark' , 'order_cate' , 'status' , 'add_time'] , 'trim'] ,
            [['name' ,  'order_cate' , 'status' , 'add_time'] , 'required','message'=>'{attribute}不能为空'] ,
            [['order_cate' , 'status'] , 'integer'] ,
            [['status'] , 'in' , 'range' => [self::STATUS_OFF,self::STATUS_ON],'message'=>'{attribute}不合法'] ,
            [['order_cate'] , 'in' , 'range' => array_keys(AgentUsersTradeLog::getOrderCateNamesForOrderRecharge()),'message'=>'{attribute}不合法'] ,
            [['add_time'] , 'safe'] ,
            [['name' , 'remark'] , 'string' , 'max' => 255] ,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID' ,
            'name'       => '模版名称' ,
            'remark'     => '备注' ,
            'order_cate' => '业务类型' ,
            'status'     => '模版状态' ,
            'add_time'   => '添加时间' ,
        ];
    }
}