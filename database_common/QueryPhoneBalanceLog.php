<?php

namespace common\models\database_common;

use Yii;

/**
 * This is the model class for table "{{%query_phone_balance_log}}".
 *
 * @property int $id
 * @property int $platform_id
 * @property int $user_id
 * @property string $qianmi_user_code
 * @property string $qianmi_user_accounts
 * @property string $phone
 * @property string $city
 * @property string $province
 * @property string $isp
 * @property string $other_info
 * @property string $balance
 * @property string $name
 * @property int $query_num
 * @property string $third_class_name
 * @property string $responses
 * @property string $requests
 * @property string $content
 * @property string $user_ip
 * @property string $absolute_url
 * @property string $add_time
 * @property string $end_time
 */
class QueryPhoneBalanceLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%query_phone_balance_log}}';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_common');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['platform_id', 'user_id'], 'required'],
            [['platform_id', 'user_id', 'query_num'], 'integer'],
            [['responses', 'requests', 'absolute_url'], 'string'],
            [['add_time', 'end_time'], 'safe'],
            [['qianmi_user_code', 'qianmi_user_accounts', 'phone', 'city', 'province', 'isp', 'other_info', 'balance', 'name', 'third_class_name', 'content', 'user_ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'platform_id' => 'Platform ID',
            'user_id' => 'User ID',
            'qianmi_user_code' => 'Qianmi User Code',
            'qianmi_user_accounts' => 'Qianmi User Accounts',
            'phone' => 'Phone',
            'city' => 'City',
            'province' => 'Province',
            'isp' => 'Isp',
            'other_info' => 'Other Info',
            'balance' => 'Balance',
            'name' => 'Name',
            'query_num' => 'Query Num',
            'third_class_name' => 'Third Class Name',
            'responses' => 'Responses',
            'requests' => 'Requests',
            'content' => 'Content',
            'user_ip' => 'User Ip',
            'absolute_url' => 'Absolute Url',
            'add_time' => 'Add Time',
            'end_time' => 'End Time',
        ];
    }
}
