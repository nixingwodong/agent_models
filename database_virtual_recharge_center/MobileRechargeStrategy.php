<?php

namespace common\models\database_virtual_recharge_center;

use Yii;

/**
 * This is the model class for table "{{%mobile_recharge_strategy}}".
 *
 * @property int $id
 * @property string $province_name
 * @property string $city_name
 * @property string $province_code 1
 * @property string $city_code
 * @property string $face_value
 * @property int $interface_id
 * @property int $is_arbitrary
 * @property string $isp_name
 * @property string $edit_time
 * @property string $add_time
 */
class MobileRechargeStrategy extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mobile_recharge_strategy}}';
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
            [['province_name', 'face_value', 'interface_id', 'is_arbitrary', 'isp_name'], 'required'],
            [['face_value'], 'number'],
            [['interface_id', 'is_arbitrary'], 'integer'],
            [['edit_time', 'add_time'], 'safe'],
            [['province_name', 'city_name', 'province_code', 'city_code'], 'string', 'max' => 50],
            [['isp_name'], 'string', 'max' => 4],
            [['province_code', 'city_code', 'isp_name', 'face_value', 'is_arbitrary'], 'unique', 'targetAttribute' => ['province_code', 'city_code', 'isp_name', 'face_value', 'is_arbitrary']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'province_name' => 'Province Name',
            'city_name' => 'City Name',
            'province_code' => 'Province Code',
            'city_code' => 'City Code',
            'face_value' => 'Face Value',
            'interface_id' => 'Interface ID',
            'is_arbitrary' => 'Is Arbitrary',
            'isp_name' => 'Isp Name',
            'edit_time' => 'Edit Time',
            'add_time' => 'Add Time',
        ];
    }
}
