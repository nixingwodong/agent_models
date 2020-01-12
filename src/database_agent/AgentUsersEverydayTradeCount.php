<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_everyday_trade_count}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $date
 * @property string $profit
 * @property string $add_time
 */
class AgentUsersEverydayTradeCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_everyday_trade_count}}';
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
            [['user_id', 'date', 'profit'], 'required'],
            [['user_id'], 'integer'],
            [['date', 'add_time'], 'safe'],
            [['profit'], 'number'],
            [['user_id', 'date'], 'unique', 'targetAttribute' => ['user_id', 'date']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'date' => 'Date',
            'profit' => 'Profit',
            'add_time' => 'Add Time',
        ];
    }
}
