<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%site_article_read_situation}}".
 *
 * @property int $id
 * @property int $article_id 公告id
 * @property int $user_id 用户id
 * @property int $status 阅读状态 1已经阅读，2未阅读
 * @property string $add_time
 * @property string $edit_time
 */
class SiteArticleReadSituation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%site_article_read_situation}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['article_id', 'user_id', 'add_time', 'edit_time'], 'required'],
            [['article_id', 'user_id', 'status'], 'integer'],
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
            'article_id' => 'Article ID',
            'user_id' => 'User ID',
            'status' => 'Status',
            'add_time' => 'Add Time',
            'edit_time' => 'Edit Time',
        ];
    }
}
