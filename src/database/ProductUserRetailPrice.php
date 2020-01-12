<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%product_user_retail_price}}".
 *
 * @property int $id
 * @property int $product_id
 * @property int $product_cate
 * @property int $user_id
 * @property string $user_retail_price
 * @property string $add_time
 * @property string $edit_time
 */
class ProductUserRetailPrice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_user_retail_price}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'product_cate', 'user_id', 'user_retail_price'], 'required'],
            [['product_id', 'product_cate', 'user_id'], 'integer'],
            [['user_retail_price'], 'number'],
            [['add_time', 'edit_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'product_id' => 'Product ID',
            'product_cate' => 'Product Cate',
            'user_id' => 'User ID',
            'user_retail_price' => 'User Retail Price',
            'add_time' => 'Add Time',
            'edit_time' => 'Edit Time',
        ];
    }
}
