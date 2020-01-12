<?php


namespace agent_models\business\article;


use agent_models\traits\YiiModelTrait;

class SiteArticleReadSituation extends \agent_models\database\SiteArticleReadSituation
{
    use YiiModelTrait;

    const STATUS_UNREAD=0;
    const STATUS_READED=1;
}