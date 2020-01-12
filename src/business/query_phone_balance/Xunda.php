<?php
/**
 * Created by PhpStorm.
 * User: liuchaofu
 * Date: 2019-06-21
 * Time: 15:51
 */

namespace agent_models\business\query_phone_balance;
class Xunda
{
    use QueryPhoneBalanceTrait;
    public $query_url = 'http://121.41.28.195:64000/Query';
    public $user      = '251651698';                      //账号
    public $key       = 'guangxi2017019chairmanbowefwdfe';//秘钥

    public function __construct($user = '' , $key = '')
    {
        if ($user) {
            $this->user = $user;
        }
        if ($key) {
            $this->key = $key;
        }
    }

    public function query($mobile)
    {

        $data             = [];
        $data['mobile']   = $mobile;
        $key              = $this->key;
        $data['user']     = $user = $this->user;
        $data['mac']      = $mac = '';
        $data['sp']       = $sp = '';
        $data['province'] = $province = '';
        $data['city']     = $city = '';
        $data['sign']     = $sign = md5($mac . $user . $mobile . $key);
        //广西联通欠费查询地址：https://upay.10010.com/npfweb/NpfWeb/Mustpayment/getMustpayment?number=15509964010&province=059&GET

        $url     = "{$this->query_url}?" . http_build_query($data);
        $this->request=$url;
        $request = file_get_contents($url);
        $encode  = mb_detect_encoding($request , ["ASCII" , 'UTF-8' , "GB2312" , "GBK" , 'BIG5' , 'EUC-CN']);
        $this->response=$request = mb_convert_encoding($request , 'UTF-8' , $encode);

        if ((strpos($request , '姓') === false && strpos($request , '额') === false) || strpos($request , '余额:,') !== false || strpos($request , '续费') !== false) {
            return $this->setErrorMsg('查询失败');
        }
        $request = str_replace('：' , ':' , $request);
        $request = str_replace('，' , ',' , $request);
        if (strpos($request , '姓名:') !== false && strpos($request , ',余额:') !== false && preg_match('/姓名:(.*?),余额:(.*)/' , $request , $match) && count($match) >= 3) {
            $this->name    = $match[1];
            $this->balance = $match[2];
        } else if (strpos($request , '姓名:') !== false && strpos($request , ',欠费:') !== false && preg_match('/姓名:(.*?),欠费:(.*)/' , $request , $match) && count($match) >= 3) {
            $this->name    = $match[1];
            $this->balance = '欠费' . $match[2];
        } else if (strpos($request , '姓名:') !== false && strpos($request , ',应缴:') !== false && preg_match('/姓名:(.*?),应缴:(.*)/' , $request , $match) && count($match) >= 3) {
            $this->name    = $match[1];
            $this->balance = '应缴' . $match[2];
            //  姓名:,应缴:0.00;
        } else {
            $request    = str_replace('姓名:' , '' , $request);
            $this->name = $request;
        }
        $this->setContent();
        return true;
    }


}