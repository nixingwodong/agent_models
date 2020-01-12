<?php

namespace agent_models\business\logs;

use agent_models\traits\YiiModelTrait;

class AgentUsersOperationLog extends \agent_models\database\AgentUsersOperationLog
{
    use YiiModelTrait;
    const LOG_TYPE_UNKNOWN                = 0;
    const LOG_TYPE_LOGIN                  = 1;
    const LOG_TYPE_EDIT_USER_RETAIL_PRICE = 2;
    const LOG_TYPE_ADD_EMPLOYEE = 3;
    const LOG_TYPE_ADD_EMPLOYEE_POST = 4;
    const LOG_TYPE_EDIT_EMPLOYEE = 5;
    const LOG_TYPE_EDIT_EMPLOYEE_POST = 6;

    const LOG_TYPES
        = [
            self::LOG_TYPE_UNKNOWN                => '其他/未知' ,
            self::LOG_TYPE_LOGIN                  => '登录账号' ,
            self::LOG_TYPE_EDIT_USER_RETAIL_PRICE => '修改订单零售价格' ,
            self::LOG_TYPE_ADD_EMPLOYEE => '添加岗位' ,
            self::LOG_TYPE_ADD_EMPLOYEE_POST => '添加岗位' ,
            self::LOG_TYPE_EDIT_EMPLOYEE => '修改员工信息' ,
            self::LOG_TYPE_EDIT_EMPLOYEE_POST => '修改岗位信息' ,
        ];

    const CLIENT_WEB=1;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['log_type'] , 'required' ] ,
            [['user_id' , 'operator_employee_id' ] , 'required' , 'skipOnEmpty' => true] ,
            [['user_id' , 'operator_employee_id' , 'log_type' , 'ip' , 'client'] , 'integer'] ,
            [['add_time'] , 'safe'] ,
            [['add_time'] , 'default' , 'value' => date('Y-m-d H:i:s')] ,
            [['client'] , 'default' , 'value' => self::CLIENT_WEB] ,
            [['browser_agent'] , 'default' , 'value' => \Yii::$app->request->userAgent] ,
            [['ip'] , 'default' , 'value' => ip2long(\Yii::$app->request->userIP)] ,
            [['user_id'] , 'default' , 'value' => \Yii::$app->user->agentUser->id] ,
            [['operator_employee_id'] , 'default' , 'value' => \Yii::$app->user->isBossLogin ? 0 : \Yii::$app->user->id] ,
            [['info'] , 'string' , 'max' => 255] ,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID' ,
            'user_id'  => '用户ID' ,
            'role_id'  => '角色' ,
            'log_type' => '类型' ,
            'info'     => '详情' ,
            'ip'       => 'Ip' ,
            'client'   => '客户端' ,
            'add_time' => '操作时间' ,
        ];
    }

    public function getIp()
    {
        return long2ip($this->ip);
    }


    public static function getLoginLog()
    {
        $loginUser = \Yii::$app->user;
        $query     = self::find()
                         ->select('add_time,ip')
                         ->where([
                             'user_id'  => $loginUser->agentUser->id ,
                             'log_type' => self::LOG_TYPE_LOGIN ,
                         ]);
        if (!$loginUser->isBossLogin) {
            $query->andWhere(['operator_employee_id' => $loginUser->id]);
        }
        $data = $query->limit(2)->asArray()->all();
        if (!$data) {
            $addNewlog = $loginUser->identity->addLoginLog();
            $loginLog  = ['add_time' => $addNewlog->add_time , 'ip' => $addNewlog->getIp()];
        } else {
            $loginLog       = count($data) >= 2 ? $data[1] : $data[0];
            $loginLog['ip'] = self::getLongIp($loginLog['ip']);
        }
        return $loginLog;
    }


    public function addLog($logType , $info)
    {
        $this->loadDefaultValues();
        $this->log_type = $logType;
        $this->info     = $info;
        return $this->save();
    }

}