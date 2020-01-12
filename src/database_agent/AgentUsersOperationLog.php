<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_operation_log}}".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $operator_employee_id 0为boss,其他员工
 * @property int $log_type
 * @property string $info 操作备注
 * @property int $ip 操作者ip
 * @property int $client 操作端（网页、客户端、安卓、苹果等）
 * @property string $browser_agent
 * @property string $add_time
 */
class AgentUsersOperationLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_operation_log}}';
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
            [['user_id', 'operator_employee_id', 'log_type', 'ip', 'browser_agent', 'add_time'], 'required'],
            [['user_id', 'operator_employee_id', 'log_type', 'ip', 'client'], 'integer'],
            [['add_time'], 'safe'],
            [['info'], 'string', 'max' => 255],
            [['browser_agent'], 'string', 'max' => 500],
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
            'operator_employee_id' => 'Operator Employee ID',
            'log_type' => 'Log Type',
            'info' => 'Info',
            'ip' => 'Ip',
            'client' => 'Client',
            'browser_agent' => 'Browser Agent',
            'add_time' => 'Add Time',
        ];
    }
}
