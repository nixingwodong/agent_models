<?php

namespace common\models\database;

use Yii;

/**
 * This is the model class for table "{{%product_mobile_recharge_price_template}}".
 *
 * @property int $id
 * @property int $product_id
 * @property string $price
 * @property int $can_buy_status
 * @property int $template_id
 * @property string $rebate
 * @property int $check_price 限制价格，0否（不限制），1是（限制）
 * @property string $add_time
 */
class ProductMobileRechargePriceTemplate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%product_mobile_recharge_price_template}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_id', 'can_buy_status', 'template_id'], 'required'],
            [['product_id', 'can_buy_status', 'template_id', 'check_price'], 'integer'],
            [['price', 'rebate'], 'number'],
            [['add_time'], 'safe'],
            [['product_id', 'template_id'], 'unique', 'targetAttribute' => ['product_id', 'template_id']],
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
            'template_id' => 'Template ID',
            'rebate' => 'Rebate',
            'check_price' => 'Check Price',
            'add_time' => 'Add Time',
        ];
    }
}
