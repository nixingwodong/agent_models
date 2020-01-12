<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%agent_users_print_settings}}".
 *
 * @property int $user_id
 * @property string $view json格式如{order_sn,on}
 * @property int $print_type 0:58mm打印机，1普通
 * @property string $content
 */
class AgentUsersPrintSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%agent_users_print_settings}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'view', 'content'], 'required'],
            [['user_id', 'print_type'], 'integer'],
            [['view'], 'string', 'max' => 500],
            [['content'], 'string', 'max' => 1000],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'view' => 'View',
            'print_type' => 'Print Type',
            'content' => 'Content',
        ];
    }
}
