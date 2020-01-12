<?php

namespace agent_models\database_virtual_recharge_center;

use Yii;

/**
 * This is the model class for table "{{%third_recharge_interface_info}}".
 *
 * @property int $id
 * @property string $method_name
 * @property string $name
 * @property string $template_sn
 * @property int $type 类型：1话费2腾讯3游戏
 * @property int $status 0已暂停,1已启动，
 * @property string $sell_interface_json_info
 * @property string $sell_user_account
 * @property string $sell_user_key
 * @property string $info 备注
 * @property int $add_time
 * @property int $get_order_thread_num 获取订单线程
 * @property int $sub_order_thread_num 提交订单线程
 * @property int $query_order_thread_num 查询订单线程
 * @property int $can_get_order 是否允许获取订单
 * @property int $can_sub_order 是否允许提交订单
 * @property int $can_query_order 是否允许查询订单
 * @property int $get_order_space_second 获取订单相隔时间
 * @property int $sub_order_space_second 提交订单相隔时间秒
 * @property int $query_order_space_second 查询订单相隔时间秒
 */
class ThirdRechargeInterfaceInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%third_recharge_interface_info}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_virtual_recharge_center');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['method_name', 'name', 'template_sn', 'type', 'sell_interface_json_info', 'sell_user_account', 'sell_user_key', 'add_time'], 'required'],
            [['type', 'status', 'add_time', 'get_order_thread_num', 'sub_order_thread_num', 'query_order_thread_num', 'can_get_order', 'can_sub_order', 'can_query_order', 'get_order_space_second', 'sub_order_space_second', 'query_order_space_second'], 'integer'],
            [['method_name', 'name', 'sell_user_account', 'sell_user_key', 'info'], 'string', 'max' => 255],
            [['template_sn'], 'string', 'max' => 50],
            [['sell_interface_json_info'], 'string', 'max' => 500],
            [['template_sn'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'method_name' => 'Method Name',
            'name' => 'Name',
            'template_sn' => 'Template Sn',
            'type' => 'Type',
            'status' => 'Status',
            'sell_interface_json_info' => 'Sell Interface Json Info',
            'sell_user_account' => 'Sell User Account',
            'sell_user_key' => 'Sell User Key',
            'info' => 'Info',
            'add_time' => 'Add Time',
            'get_order_thread_num' => 'Get Order Thread Num',
            'sub_order_thread_num' => 'Sub Order Thread Num',
            'query_order_thread_num' => 'Query Order Thread Num',
            'can_get_order' => 'Can Get Order',
            'can_sub_order' => 'Can Sub Order',
            'can_query_order' => 'Can Query Order',
            'get_order_space_second' => 'Get Order Space Second',
            'sub_order_space_second' => 'Sub Order Space Second',
            'query_order_space_second' => 'Query Order Space Second',
        ];
    }
}
