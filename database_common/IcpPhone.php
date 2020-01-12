<?php

namespace common\models\database_common;

use Yii;

/**
 * This is the model class for table "{{%icp_phone}}".
 *
 * @property int $id
 * @property int $prefix 网络识别号，手机号前三位，即运营商
 * @property int $phone 手机号后4到11位
 * @property string $province_name 省
 * @property string $city_name 市
 * @property string $isp_name 运营商
 * @property string $post_code 邮编
 * @property string $city_code 固话前缀编码
 * @property int $area_code 地区行政编码
 * @property string $types 运营商业务
 * @property int $is_mvno 是否是虚拟运营商,1是，0不是
 * @property string $mvno_isp 虚拟运营商所属顶级运营商
 * @property int $mvno_id
 * @property int $province_code 省级行政单位编号
 * @property int $status_icp 号码状态,1正常,2维护
 * @property int $add_time
 * @property int $edit_time 1为正常nomal，0为维护maintain
 */
class IcpPhone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%icp_phone}}';
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
            [['prefix', 'phone', 'area_code', 'is_mvno', 'mvno_id', 'province_code', 'status_icp', 'add_time', 'edit_time'], 'integer'],
            [['phone', 'province_name', 'city_name', 'isp_name', 'post_code', 'city_code', 'area_code', 'province_code'], 'required'],
            [['province_name', 'city_name', 'isp_name', 'post_code', 'city_code', 'mvno_isp'], 'string', 'max' => 20],
            [['types'], 'string', 'max' => 50],
            [['phone'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'prefix' => 'Prefix',
            'phone' => 'Phone',
            'province_name' => 'Province Name',
            'city_name' => 'City Name',
            'isp_name' => 'Isp Name',
            'post_code' => 'Post Code',
            'city_code' => 'City Code',
            'area_code' => 'Area Code',
            'types' => 'Types',
            'is_mvno' => 'Is Mvno',
            'mvno_isp' => 'Mvno Isp',
            'mvno_id' => 'Mvno ID',
            'province_code' => 'Province Code',
            'status_icp' => 'Status Icp',
            'add_time' => 'Add Time',
            'edit_time' => 'Edit Time',
        ];
    }
}
