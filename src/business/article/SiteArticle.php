<?php

namespace agent_models\business\article;
use agent_models\traits\YiiModelTrait;

class SiteArticle extends \agent_models\database\SiteArticle
{
    use YiiModelTrait;

    const CATE_ID_UNKNOWN                 = 0;//未知
    const CATE_ID_QUESTIONS_INDEX         = 1;//首页常见问题
    const CATE_ID_QUESTIONS_FINANCE       = 2;//财务常见问题
    const CATE_ID_QUESTIONS_BUY           = 3;//购物常见问题
    const CATE_ID_NOTICE_INDEX_POP_UP     = 4;//首页弹窗公告
    const CATE_ID_NOTICE_INDEX_NOT_POP_UP = 5;//首页非弹窗公告
    const CATE_ID_NOTICE_HOME_POP_UP      = 6;//登录主页弹窗公告
    const CATE_ID_NOTICE_HOME_NOT_POP_UP  = 7;//登录主页非弹窗公告
    const CATE_ID_JOIN_INDEX              = 8;//首页招商加盟

    const STATUS_DRAFT     = 0;//草稿
    const STATUS_PUBLISHED = 1;//已发布

    const STATUSES
        = [
            self::STATUS_DRAFT     => '草稿' ,
            self::STATUS_PUBLISHED => '已发布' ,
        ];

    const CATES
        = [
            self::CATE_ID_UNKNOWN                 => '未知' ,
            self::CATE_ID_QUESTIONS_INDEX         => '首页常见问题' ,
            self::CATE_ID_QUESTIONS_FINANCE       => '财务常见问题' ,
            self::CATE_ID_QUESTIONS_BUY           => '购物常见问题' ,
            self::CATE_ID_NOTICE_INDEX_POP_UP     => '首页弹窗公告' ,
            self::CATE_ID_NOTICE_INDEX_NOT_POP_UP => '首页非弹窗公告' ,
            self::CATE_ID_NOTICE_HOME_POP_UP      => '登录主页弹窗公告' ,
            self::CATE_ID_NOTICE_HOME_NOT_POP_UP  => '登录主页非弹窗公告' ,
            self::CATE_ID_JOIN_INDEX              => '首页招商加盟' ,
        ];
}