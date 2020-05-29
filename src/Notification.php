<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/6/26
 * Time: 17:32
 */

namespace Pqf\Notification;

use Carbon\Carbon;

class Notification
{
    protected $factory;
    public function channel($name)
    {
        $channel_name="Pqf\\Channel\\". ucfirst($name);
        if(!class_exists($channel_name,false)){
            return new NotificationResponse(1,printf("Notification Channel %s Doesn't exist",$name));
        }
        $factory=new $channel_name;
        $this->factory=$factory;
        return $this;

    }

    public function sendSms($account, $content)
    {
        if(!is_callable($this->factory)){
            return new NotificationResponse(1,'Please chose the send channel');
        }
        return call_user_func_array([$this->factory,'sendSms'],[$account,$content]);

    }

    protected function checkVerifyCode($account,$code=null,$type=1){
        $key='pqfno_'.$account.'_code'.$type;
        if($code==cache()->get($key)&&$code){
            return true;
        }else{
            return false;
        }
    }
    //发送验证码并保存数据库
    public function sendVerifyCode($account,$content='',$type=1)
    {
        $key='pqfno_'.$account.'_code'.$type;
        $code = mt_rand(100000, 999999);
        if(!$content){
            $content = trans('pqfno::sms.sms_temp',['code'=>$code,'minutes'=>config('pqfno.timeout')]);
        }
        $res = $this->sendSms($account, $content);
        if ($res->success == NotificationResponse::SUCCESS_CODE) {
            cache($key,$code,config('pqfno.timeout')*60);
            return [
                'success' => true,
                'message' => trans('pqfno::sms.send_success'),
            ];
        }

        return [
            'success' => false,
            'message' =>trans('pqfno::sms.send_failed'),
        ];
    }
}