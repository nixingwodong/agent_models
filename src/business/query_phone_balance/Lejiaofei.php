<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-06-21
 * Time: 15:51
 */

namespace agent_models\business\query_phone_balance;

use nixingwodong\cf_all\tool\HttpRequest;

class Lejiaofei
{
    use QueryPhoneBalanceTrait;
    public $query_url = 'http://api.ljiaofei.com/esales/do.asp';
    public $uid       = '15678527068';                     //账号
    public $sn        = '325598d8fdf31f2a8bce7c27dd952ffc';//秘钥

    public function __construct($uid = '' , $sn = '')
    {
        if ($uid) {
            $this->uid = $uid;
        }
        if ($sn) {
            $this->sn = $sn;
        }
    }

    public function query($mobile)
    {
        $url = $this->query_url;
        $uid = $this->uid;
        $hm  = $mobile;
        $ob  = "mobileinfo";
        $sn  = $this->sn;
        $key = md5("uid={$uid}&hm={$hm}&sn={$sn}");

        $data = "<?xml version=\"1.0\" encoding=\"gb2312\"?>
            <items>
             <ob>{$ob}</ob>
             <uid>{$uid}</uid>
             <hm>{$hm}</hm> 
             <key>{$key}</key>
            </items>";

        $request = $this->request = (new HttpRequest($url , $data))->post();
        if ($request->hasErrors()) {
            return $this->setErrorMsg('请求失败');
        }
        $response = $this->response = $request->setResponseXmlAsArray();

        if (!empty($response['err'])) {
            return $this->setErrorMsg('查询失败，错误信息：' . $response['err']);
        }
        if($response['name']=='查*' && $response['money']==0){
            return $this->setErrorMsg('该号码暂不支持查询');
        }
        $this->name    = $response['name'];
        $this->balance = $response['money'];
        $this->setContent();
        return true;
    }


}