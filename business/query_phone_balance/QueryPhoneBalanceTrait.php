<?php
namespace common\models\business\query_phone_balance;
use common\models\traits\GeneralModelTrait;

trait QueryPhoneBalanceTrait{
    use GeneralModelTrait;
    public $phone;
    public $response;
    public $request;
    public $name       = '';
    public $balance    = '';
    public $otherInfo = '';
    public $content = '';

    public $thirdClassName = __CLASS__;
    public $className = __CLASS__;

    public function setContent(){
        $this->content = "姓名:{$this->name},余额:{$this->balance}";
    }

    public function getContent(){
        return $this->content;
    }


}