<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-06-21
 * Time: 15:48
 */

namespace common\models\business\query_phone_balance;

use common\models\database_common\QueryPhoneBalanceLog;
use nixingwodong\cf_all\yii_business\Icp;

/**
 * Class QueryPhoneBalance
 * @package common\models\business\query_phone_balance
 * @property Icp $icp;
 */
class QueryPhoneBalance
{
    use QueryPhoneBalanceTrait;

    public $icp;
    public $queryNum  = 0;
    public $requests  = [];
    public $responses = [];

    const PLATFORM_ID_AGENT  = 1;
    const PLATFORM_ID_QIANMI = 100;

    const PLATFORMS
        = [
            self::PLATFORM_ID_AGENT  => '代理商' ,
            self::PLATFORM_ID_QIANMI => '千米' ,
        ];


    public function __construct($phone)
    {
        $this->phone = $phone;
        if (!$phone) {
            $this->setErrorMsg('手机号码不能为空');
            return;
        }
        $this->icp = new Icp();
        if (!$this->icp->getPhoneIcp($phone)) {
            $this->setErrorMsg($this->icp->getErrorMsg());
        }
    }


    public function queryRepeat($platform_id , $user_id , $repeat_num = 0 , $qianmi_user_code = '' , $qianmi_user_accounts = '')
    {
        if ($this->isError()) {
            return false;
        }
        do {
            $this->queryNum++;
            $query = $this->query();
        } while (!$query && $this->queryNum < $repeat_num);

        $add_log                   = new QueryPhoneBalanceLog();
        $add_log->user_id          = $user_id;
        $add_log->platform_id      = $platform_id;
        $add_log->phone            = $this->phone;
        $add_log->isp              = $this->icp->ispName;
        $add_log->province         = $this->icp->provinceName;
        $add_log->city             = $this->icp->cityName;
        $add_log->name             = $this->name;
        $add_log->balance          = $this->balance;
        $add_log->content          = $this->content;
        $add_log->third_class_name = $this->thirdClassName;
        $add_log->query_num        = $this->queryNum;
        $add_log->user_ip          = \Yii::$app->request->userIP;
        $add_log->absolute_url     = \Yii::$app->request->absoluteUrl;

        $add_log->requests  = json_encode($this->requests , JSON_UNESCAPED_UNICODE);
        $add_log->responses = json_encode($this->responses , JSON_UNESCAPED_UNICODE);;
        $add_log->qianmi_user_code     = $qianmi_user_code;
        $add_log->qianmi_user_accounts = $qianmi_user_accounts;
        $add_log->add_time             = date('Y-m-d H:i:s');

        $add_log->save();
        return $query;
    }


    private function query()
    {
        if ($this->icp->ispName == $this->icp::ISP_NAME_UNICOM) {
            return $this->queryByForeach([Lejiaofei::class , Ujiaofei::class]);
        }
        $query = $this->queryByForeach([Xunda::class]);
        if (!$query) {
            sleep(1);
        }
        return $query;
    }


    private function queryByForeach($classNames)
    {
        foreach ($classNames as $className) {
            $model             = new $className();
            $query             = $model->query($this->phone);
            $this->requests[]  = $model->request;
            $this->responses[] = $model->response;
            if ($query) {
                $this->name           = $model->name;
                $this->balance        = $model->balance;
                $this->otherInfo      = $model->otherInfo;
                $this->content        = $model->content;
                $this->thirdClassName = $model->thirdClassName;
                return true;
            }
        }
        return $this->setErrorMsg($model->getErrorMsg());
    }
}