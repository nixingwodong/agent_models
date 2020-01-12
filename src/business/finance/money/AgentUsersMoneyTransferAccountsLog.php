<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-03-02
 * Time: 18:18
 */

namespace agent_models\business\finance\money;

class AgentUsersMoneyTransferAccountsLog extends \agent_models\database\AgentUsersMoneyTransferAccountsLog
{

    public static function buildAnOrderSn($prefix = '')
    {
        return $prefix . ltrim(date('YmdHis') , '20') . str_pad(mt_rand(1 , 99999) , 5 , '0' , STR_PAD_LEFT);
    }


}