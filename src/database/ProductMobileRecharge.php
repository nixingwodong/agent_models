<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%product_mobile_recharge}}".
 *
 * @property int $id
 * @property string $name
 * @property string $isp_name
 * @property int $mvno_id
 * @property int $province_code
 * @property int $area_code
 * @property string $face_value
 * @property int $arbitrarily 是否为任意充0否，1是
 * @property string $can_buy_scope
 * @property int $recharge_speed 充值速度，1快充，2慢充
 * @property int $product_status
 * @property string $add_time
 */
class ProductMobileRecharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_mobile_recharge}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'isp_name', 'mvno_id', 'province_code', 'area_code', 'face_value', 'product_status'], 'required'],
            [['mvno_id', 'province_code', 'area_code', 'arbitrarily', 'recharge_speed', 'product_status'], 'integer'],
            [['face_value'], 'number'],
            [['add_time'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['isp_name'], 'string', 'max' => 2],
            [['can_buy_scope'], 'string', 'max' => 50],
            [['isp_name', 'mvno_id', 'province_code', 'area_code', 'face_value', 'arbitrarily', 'recharge_speed'], 'unique', 'targetAttribute' => ['isp_name', 'mvno_id', 'province_code', 'area_code', 'face_value', 'arbitrarily', 'recharge_speed']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'isp_name' => 'Isp Name',
            'mvno_id' => 'Mvno ID',
            'province_code' => 'Province Code',
            'area_code' => 'Area Code',
            'face_value' => 'Face Value',
            'arbitrarily' => 'Arbitrarily',
            'can_buy_scope' => 'Can Buy Scope',
            'recharge_speed' => 'Recharge Speed',
            'product_status' => 'Product Status',
            'add_time' => 'Add Time',
        ];
    }
}
