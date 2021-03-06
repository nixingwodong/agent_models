<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%product_tencent_recharge_price_user}}".
 *
 * @property int $id
 * @property int $product_id
 * @property string $price
 * @property int $can_buy_status
 * @property int $user_id
 * @property string $rebate
 * @property int $check_price 限制价格，0否（不限制），1是（限制）
 * @property string $add_time
 */
class ProductTencentRechargePriceUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_tencent_recharge_price_user}}';
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
            [['product_id', 'can_buy_status', 'user_id'], 'required'],
            [['product_id', 'can_buy_status', 'user_id', 'check_price'], 'integer'],
            [['price', 'rebate'], 'number'],
            [['add_time'], 'safe'],
            [['product_id', 'user_id'], 'unique', 'targetAttribute' => ['product_id', 'user_id']],
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
            'price' => 'Price',
            'can_buy_status' => 'Can Buy Status',
            'user_id' => 'User ID',
            'rebate' => 'Rebate',
            'check_price' => 'Check Price',
            'add_time' => 'Add Time',
        ];
    }
}
