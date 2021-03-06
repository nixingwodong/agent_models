<?php


namespace agent_models\traits;


use agent_models\Employee;
use agent_models\User;

/**
 * Trait YiiModelValidatePassWordTrait
 * @package agent_models\traits
 * @property User $agentUser
 * @property  \yii\web\IdentityInterface|null|\agent_models\User|Employee $user
 */
trait YiiModelValidatePassWordTrait
{

    public $user;
    public $agentUser;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->agentUser = $this->getAgentUser();
        $this->user = $this->getUser();
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateLoginUserPassword($attribute , $params)
    {
        if (empty($this->$attribute)) {
            $this->addError($attribute , '密码不能为空');
            return;
        }
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute , '用户不存在');
                return;
            }
            if (!$user->validatePassword($this->$attribute)) {

                $this->addError($attribute , '密码错误');
            }
        }
    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateAgentUserPassword($attribute , $params)
    {

        if (empty($this->$attribute)) {
            $this->addError($attribute , '密码不能为空');
            return;
        }
        if (!$this->hasErrors()) {
            $agentUser = $this->getAgentUser();
            if (!$agentUser) {
                $this->addError($attribute , '用户不存在');
                return;
            }
            if (!$agentUser->validatePassword($this->$attribute)) {
                $this->addError($attribute , '密码错误');
                return;
            }
        }
    }


    /**
     * @param $attribute
     * @param $params
     */
    public function validateLoginUserPayPassword($attribute , $params)
    {
        if (empty($this->$attribute)) {
            $this->addError($attribute , '密码不能为空');
            return;
        }
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute , '用户不存在');
                return;
            }
            if (!$user->validatePassword($this->$attribute)) {
                $this->addError($attribute , '密码错误');
                return;
            }
        }

    }

    /**
     * @param $attribute
     * @param $params
     */
    public function validateAgentUserPayPassword($attribute , $params)
    {
        if (empty($this->$attribute)) {
            $this->addError($attribute , '密码不能为空');
            return;
        }
        if (!$this->hasErrors()) {
            $agentUser = $this->getAgentUser();
            if (!$agentUser) {
                $this->addError($attribute , '用户不存在');
                return;
            }
            if (!$agentUser->validatePayPassword($this->$attribute)) {
                $this->addError($attribute , '密码错误');
                return;
            }
        }
    }


    /**
     * @return Employee|User|\yii\web\IdentityInterface|null
     */
    private function getUser()
    {
        return $this->user ?: \Yii::$app->user->identity;
    }

    private function getAgentUser()
    {
        return $this->agentUser ?: \Yii::$app->user->agentUser;
    }
}