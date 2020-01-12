<?php

namespace common\models\database;

use Yii;

/**
 * This is the model class for table "{{%product_tencent_recharge}}".
 *
 * @property int $id
 * @property string $name
 * @property int $tencent_cate
 * @property int $unit
 * @property string $face_value
 * @property string $can_buy_scope
 * @property int $product_status
 * @property string $add_time
 */
class ProductTencentRecharge extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_tencent_recharge}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tencent_cate', 'unit', 'face_value', 'product_status'], 'required'],
            [['tencent_cate', 'unit', 'product_status'], 'integer'],
            [['face_value'], 'number'],
            [['add_time'], 'safe'],
            [['name'], 'string', 'max' => 20],
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
            'tencent_cate' => 'Tencent Cate',
            'unit' => 'Unit',
            'face_value' => 'Face Value',
            'can_buy_scope' => 'Can Buy Scope',
            'product_status' => 'Product Status',
            'add_time' => 'Add Time',
        ];
    }
}
