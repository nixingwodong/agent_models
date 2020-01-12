<?php

namespace common\models\database_virtual_recharge_center;

use Yii;

/**
 * This is the model class for table "{{%icp_province_city_code}}".
 *
 * @property int $id
 * @property string $province_name
 * @property string $city_name
 * @property string $province_code 1
 * @property string $city_code
 * @property int $add_time
 */
class IcpProvinceCityCode extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%icp_province_city_code}}';
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
            [['province_name'], 'required'],
            [['add_time'], 'integer'],
            [['province_name', 'city_name', 'province_code', 'city_code'], 'string', 'max' => 50],
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
            'add_time' => 'Add Time',
        ];
    }
}
