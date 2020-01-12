<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%qianmi_users}}".
 *
 * @property int $id
 * @property string $code
 * @property string $username
 * @property string $real_name
 * @property string $level
 * @property string $type
 * @property string $shop_name
 * @property string $area_code
 * @property string $shop_address
 * @property string $money
 * @property string $parent_code
 * @property string $register_time
 * @property string $login_time
 * @property string $qq
 * @property string $phone
 * @property string $tel
 */
class QianmiUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%qianmi_users}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['register_time', 'login_time'], 'safe'],
            [['code', 'username', 'real_name', 'level', 'type', 'shop_name', 'area_code', 'shop_address', 'money', 'parent_code', 'qq', 'phone', 'tel'], 'string', 'max' => 255],
            [['username'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'username' => 'Username',
            'real_name' => 'Real Name',
            'level' => 'Level',
            'type' => 'Type',
            'shop_name' => 'Shop Name',
            'area_code' => 'Area Code',
            'shop_address' => 'Shop Address',
            'money' => 'Money',
            'parent_code' => 'Parent Code',
            'register_time' => 'Register Time',
            'login_time' => 'Login Time',
            'qq' => 'Qq',
            'phone' => 'Phone',
            'tel' => 'Tel',
        ];
    }
}
