<?php

namespace Pqf\Notification\Channel;

use Illuminate\Support\Facades\Log;
use Pqf\Notification\Contract\SendMessage;
use Pqf\Notification\NotificationResponse;
use Illuminate\Support\Facades\Mail;

class Email implements SendMessage
{
    public function send($account, $content)
    {
        try{
            Mail::raw($content, function ($message)use ($account){
                $message->subject($account);
                // 指定发送到哪个邮箱账号
                $message->to($account);
            });
            return new NotificationResponse(NotificationResponse::SUCCESS_CODE , trans('pqfno::sms.send_success'));
        }catch (\Exception $e){
            Log::info('send_sms_err',['code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new NotificationResponse(NotificationResponse::FAIL_CODE ,trans('pqfno::sms.send_failed'));
        }
    }
}
