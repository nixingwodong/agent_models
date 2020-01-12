<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users}}".
 *
 * @property int $id
 * @property string $real_name
 * @property string $id_card_no
 * @property string $nickname
 * @property string $username
 * @property string $login_mobile
 * @property string $password
 * @property string $password_hash
 * @property string $pay_password
 * @property string $auth_key
 * @property int $status
 * @property string $qq_number
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $pay_password_reset_token
 * @property string $pay_password_hash
 * @property string $shop_name
 * @property string $shop_address
 * @property string $money
 * @property string $rebate_balance
 * @property int $agency_type
 * @property int $parent_dealer_user_id
 * @property string $skin
 * @property int $mobile_recharge_price_template_id
 * @property int $telephone_recharge_price_template_id
 * @property int $tencent_recharge_price_template_id
 * @property int $game_recharge_price_template_id
 * @property int $is_lock_screen
 * @property int $is_auto_play
 * @property int $is_show_cost
 * @property string $add_time
 * @property string $eidt_time
 * @property string $qianmi_code
 * @property string $parent_qianmi_code
 * @property string $login_time
 * @property string $contact_phone
 */
class AgentUsers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users}}';
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
            [['real_name', 'id_card_no', 'nickname', 'username', 'login_mobile', 'password', 'password_hash', 'pay_password', 'status', 'qq_number', 'password_reset_token', 'verification_token', 'shop_name', 'login_time'], 'required'],
            [['status', 'agency_type', 'parent_dealer_user_id', 'mobile_recharge_price_template_id', 'telephone_recharge_price_template_id', 'tencent_recharge_price_template_id', 'game_recharge_price_template_id', 'is_lock_screen', 'is_auto_play', 'is_show_cost'], 'integer'],
            [['money', 'rebate_balance'], 'number'],
            [['add_time', 'eidt_time', 'login_time'], 'safe'],
            [['real_name', 'shop_name'], 'string', 'max' => 50],
            [['id_card_no'], 'string', 'max' => 18],
            [['nickname', 'password', 'password_hash', 'pay_password', 'auth_key', 'password_reset_token', 'verification_token', 'pay_password_reset_token', 'pay_password_hash', 'shop_address', 'qianmi_code', 'parent_qianmi_code', 'contact_phone'], 'string', 'max' => 255],
            [['username', 'login_mobile', 'qq_number'], 'string', 'max' => 11],
            [['skin'], 'string', 'max' => 20],
            [['username'], 'unique'],
            [['login_mobile'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'real_name' => 'Real Name',
            'id_card_no' => 'Id Card No',
            'nickname' => 'Nickname',
            'username' => 'Username',
            'login_mobile' => 'Login Mobile',
            'password' => 'Password',
            'password_hash' => 'Password Hash',
            'pay_password' => 'Pay Password',
            'auth_key' => 'Auth Key',
            'status' => 'Status',
            'qq_number' => 'Qq Number',
            'password_reset_token' => 'Password Reset Token',
            'verification_token' => 'Verification Token',
            'pay_password_reset_token' => 'Pay Password Reset Token',
            'pay_password_hash' => 'Pay Password Hash',
            'shop_name' => 'Shop Name',
            'shop_address' => 'Shop Address',
            'money' => 'Money',
            'rebate_balance' => 'Rebate Balance',
            'agency_type' => 'Agency Type',
            'parent_dealer_user_id' => 'Parent Dealer User ID',
            'skin' => 'Skin',
            'mobile_recharge_price_template_id' => 'Mobile Recharge Price Template ID',
            'telephone_recharge_price_template_id' => 'Telephone Recharge Price Template ID',
            'tencent_recharge_price_template_id' => 'Tencent Recharge Price Template ID',
            'game_recharge_price_template_id' => 'Game Recharge Price Template ID',
            'is_lock_screen' => 'Is Lock Screen',
            'is_auto_play' => 'Is Auto Play',
            'is_show_cost' => 'Is Show Cost',
            'add_time' => 'Add Time',
            'eidt_time' => 'Eidt Time',
            'qianmi_code' => 'Qianmi Code',
            'parent_qianmi_code' => 'Parent Qianmi Code',
            'login_time' => 'Login Time',
            'contact_phone' => 'Contact Phone',
        ];
    }
}
