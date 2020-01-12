<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_employee_posts}}".
 *
 * @property int $id
 * @property int $boss_user_id
 * @property string $name
 * @property string $remark
 * @property string $power_ids
 * @property int $employee_num
 * @property string $add_time
 * @property string $edit_time
 */
class AgentUsersEmployeePosts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_employee_posts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['boss_user_id', 'name', 'power_ids', 'edit_time'], 'required'],
            [['boss_user_id', 'employee_num'], 'integer'],
            [['power_ids'], 'string'],
            [['add_time', 'edit_time'], 'safe'],
            [['name'], 'string', 'max' => 120],
            [['remark'], 'string', 'max' => 200],
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
            'name' => 'Name',
            'remark' => 'Remark',
            'power_ids' => 'Power Ids',
            'employee_num' => 'Employee Num',
            'add_time' => 'Add Time',
            'edit_time' => 'Edit Time',
        ];
    }
}
