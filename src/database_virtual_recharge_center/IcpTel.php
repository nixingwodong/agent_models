<?php

namespace agent_models\database_virtual_recharge_center;

use Yii;

/**
 * This is the model class for table "{{%icp_tel}}".
 *
 * @property int $id
 * @property string $prefix 固话号码前缀
 * @property string $phone 固定电话号码
 * @property string $province_name 所在省
 * @property string $city_name 所在市
 * @property string $isp_name 运营商
 * @property string $post_code 邮编
 * @property string $city_code 固话前缀
 * @property string $area_code 市行政编码
 * @property string $types 运营商业务类型
 * @property string $province_code 省级行政单位编码
 * @property int $status_icp 号码状态,1正常,2维护
 * @property int $is_mvno
 * @property string $mvno_isp
 * @property int $mvno_id
 * @property int $add_time
 * @property int $edit_time 1为正常nomal，0为维护maintain
 */
class IcpTel extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%icp_tel}}';
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
            [['status_icp', 'is_mvno', 'mvno_id', 'add_time', 'edit_time'], 'integer'],
            [['is_mvno', 'mvno_id'], 'required'],
            [['prefix', 'phone', 'province_name', 'city_name', 'isp_name', 'post_code', 'city_code', 'area_code', 'types', 'province_code', 'mvno_isp'], 'string', 'max' => 255],
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
            'province_code' => 'Province Code',
            'status_icp' => 'Status Icp',
            'is_mvno' => 'Is Mvno',
            'mvno_isp' => 'Mvno Isp',
            'mvno_id' => 'Mvno ID',
            'add_time' => 'Add Time',
            'edit_time' => 'Edit Time',
        ];
    }
}
