<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_employees}}".
 *
 * @property int $id
 * @property int $boss_user_id
 * @property int $post_id
 * @property string $real_name
 * @property string $id_card_no
 * @property string $nickname
 * @property string $username
 * @property string $daily_trading_limit_money
 * @property string $login_mobile
 * @property string $password
 * @property string $password_hash
 * @property string $pay_password
 * @property string $auth_key
 * @property int $status
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $pay_password_reset_token
 * @property string $pay_password_hash
 * @property string $skin
 * @property int $is_lock_screen
 * @property int $is_auto_play
 * @property int $is_show_cost
 * @property int $is_can_show_cost
 * @property int $is_can_show_balance
 * @property string $add_time
 * @property string $eidt_time
 */
class AgentUsersEmployees extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_employees}}';
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
            [['boss_user_id', 'post_id', 'real_name', 'id_card_no', 'nickname', 'username', 'login_mobile', 'password', 'password_hash', 'pay_password', 'status', 'password_reset_token', 'verification_token', 'is_can_show_cost', 'is_can_show_balance', 'eidt_time'], 'required'],
            [['boss_user_id', 'post_id', 'status', 'is_lock_screen', 'is_auto_play', 'is_show_cost', 'is_can_show_cost', 'is_can_show_balance'], 'integer'],
            [['daily_trading_limit_money'], 'number'],
            [['add_time', 'eidt_time'], 'safe'],
            [['real_name'], 'string', 'max' => 50],
            [['id_card_no'], 'string', 'max' => 18],
            [['nickname', 'password', 'password_hash', 'pay_password', 'auth_key', 'password_reset_token', 'verification_token', 'pay_password_reset_token', 'pay_password_hash', 'skin'], 'string', 'max' => 255],
            [['username', 'login_mobile'], 'string', 'max' => 11],
            [['boss_user_id', 'username'], 'unique', 'targetAttribute' => ['boss_user_id', 'username']],
            [['boss_user_id', 'login_mobile'], 'unique', 'targetAttribute' => ['boss_user_id', 'login_mobile']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'boss_user_id' => 'Boss User ID',
            'post_id' => 'Post ID',
            'real_name' => 'Real Name',
            'id_card_no' => 'Id Card No',
            'nickname' => 'Nickname',
            'username' => 'Username',
            'daily_trading_limit_money' => 'Daily Trading Limit Money',
            'login_mobile' => 'Login Mobile',
            'password' => 'Password',
            'password_hash' => 'Password Hash',
            'pay_password' => 'Pay Password',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'password_reset_token' => 'Password Reset Token',
            'verification_token' => 'Verification Token',
            'pay_password_reset_token' => 'Pay Password Reset Token',
            'pay_password_hash' => 'Pay Password Hash',
            'skin' => 'Skin',
            'is_lock_screen' => 'Is Lock Screen',
            'is_auto_play' => 'Is Auto Play',
            'is_show_cost' => 'Is Show Cost',
            'is_can_show_cost' => 'Is Can Show Cost',
            'is_can_show_balance' => 'Is Can Show Balance',
            'add_time' => 'Add Time',
            'eidt_time' => 'Eidt Time',
        ];
    }
}
