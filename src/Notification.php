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
    public $factory;
    public $chanel_name;
    public function channel($name)
    {
        $channel_name="Pqf\\Notification\\Channel\\".ucfirst($name);
        try{
            $factory=new $channel_name;
            $this->factory=$factory;
            $this->chanel_name=$name;
            return $this;
        }catch (\Exception $e){
            throw new \Exception(sprintf("Notification Channel %s Doesn't exist",$this->chanel_name));
        }
    }
    public function sendSms($account, $content)
    {
        if(!is_callable([$this->factory,'send'])){
            return [
                'success' => false,
                'message' =>sprintf("Notification Channel %s Doesn't exist",$this->chanel_name),
            ];
        }
        return call_user_func_array([$this->factory,'send'],[$account,$content]);

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