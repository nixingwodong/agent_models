<?php

namespace agent_models\traits;
use yii\base\Model;

trait  GeneralModelTrait
{
    public  $errorCode;
    public  $errorMsg;


    /**
     * @return mixed
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }
    /**
     * @return mixed
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }


    /**
     * @param $errorCode
     * @param string $errorMsg
     * @return bool
     */
    private function setErrorByErrorCode($errorCode , $errorMsg = '')
    {
        $this->errorCode = $errorCode;
        $this->errorMsg  = $errorMsg ?: self::ERROR_CODES[$errorCode];
        return false;
    }

    /**
     * @param $errorMsg
     * @return bool
     */
    private function setErrorMsg($errorMsg)
    {
        $this->errorMsg = $errorMsg;
        return false;
    }

    public function isError()
    {
        return !empty($this->getErrorMsg());
    }


    /**
     * @param Model $model
     * @param $attributes //限制修改的字段
     * @return int
     */
    public static function checkAndSetUpDataByModel(Model $model , $params , $attributes)
    {
        $up_num = 0;
        foreach ($attributes as $attribute) {
            if (empty($params[$attribute])) {
                continue;
            }
            $value = trim($params[$attribute]);
            if ($model->$attribute != $value) {
                $model->$attribute = $value;
                $up_num++;
            }
        }
        return $up_num;
    }

    /**
     * @param Model $model
     * @param $params
     * @param $attributes //限制修改的字段
     * @return array
     */
    public static function upDataByModel(Model $model , $params , $attributes)
    {
        $up_num = self::checkAndSetUpDataByModel($model , $params , $attributes);
        if (!$up_num) {
            return ['status' => false , 'msg' => '没有修改','up_num'=>$up_num];
        }
        if (!$model->save()) {
            return ['status' => false , 'msg' => current($model->getFirstErrors()) , 'errors' => $model->getFirstErrors(),'up_num'=>$up_num];
        }
        return ['status' => true , 'msg' => '保存成功','up_num'=>$up_num];
    }
    public static function getNowTime(){
        return date('Y-m-d H:i:s');
    }



}