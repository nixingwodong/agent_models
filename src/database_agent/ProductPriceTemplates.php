<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%product_price_templates}}".
 *
 * @property int $id
 * @property string $name
 * @property string $remark
 * @property int $order_cate
 * @property int $status
 * @property string $add_time
 */
class ProductPriceTemplates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_price_templates}}';
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
            [['name', 'order_cate', 'status', 'add_time'], 'required'],
            [['order_cate', 'status'], 'integer'],
            [['add_time'], 'safe'],
            [['name', 'remark'], 'string', 'max' => 255],
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
            'remark' => 'Remark',
            'order_cate' => 'Order Cate',
            'status' => 'Status',
            'add_time' => 'Add Time',
        ];
    }
}
