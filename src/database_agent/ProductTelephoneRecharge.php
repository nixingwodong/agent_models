<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%product_telephone_recharge}}".
 *
 * @property int $id
 * @property string $name
 * @property string $isp
 * @property int $province_id
 * @property int $area_id
 * @property string $face_value
 * @property int $arbitrarily 是否为任意充0否，1是
 * @property string $can_buy_scope
 * @property int $product_status
 * @property string $add_time
 */
class ProductTelephoneRecharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_telephone_recharge}}';
    }
    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_agent');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'isp', 'province_id', 'area_id', 'face_value', 'product_status'], 'required'],
            [['province_id', 'area_id', 'arbitrarily', 'product_status'], 'integer'],
            [['face_value'], 'number'],
            [['add_time'], 'safe'],
            [['name'], 'string', 'max' => 20],
            [['isp'], 'string', 'max' => 2],
            [['can_buy_scope'], 'string', 'max' => 50],
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
            'isp' => 'Isp',
            'province_id' => 'Province ID',
            'area_id' => 'Area ID',
            'face_value' => 'Face Value',
            'arbitrarily' => 'Arbitrarily',
            'can_buy_scope' => 'Can Buy Scope',
            'product_status' => 'Product Status',
            'add_time' => 'Add Time',
        ];
    }
}
