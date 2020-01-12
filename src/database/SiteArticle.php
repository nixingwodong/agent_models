<?php

namespace agent_models\database;

use Yii;

/**
 * This is the model class for table "{{%site_article}}".
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $cate_id
 * @property int $status
 * @property string $images
 * @property string $add_time
 * @property string $pub_time
 * @property string $edit_time
 */
class SiteArticle extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%site_article}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content', 'cate_id', 'status', 'add_time', 'pub_time', 'edit_time'], 'required'],
            [['content'], 'string'],
            [['cate_id', 'status'], 'integer'],
            [['add_time', 'pub_time', 'edit_time'], 'safe'],
            [['title', 'images'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'cate_id' => 'Cate ID',
            'status' => 'Status',
            'images' => 'Images',
            'add_time' => 'Add Time',
            'pub_time' => 'Pub Time',
            'edit_time' => 'Edit Time',
        ];
    }
}
