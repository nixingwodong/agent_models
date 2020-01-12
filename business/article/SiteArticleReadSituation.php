<?php


namespace common\models\business\article;


use common\models\traits\YiiModelTrait;

class SiteArticleReadSituation extends \common\models\database\SiteArticleReadSituation
{
    use YiiModelTrait;

    const STATUS_UNREAD=0;
    const STATUS_READED=1;
}