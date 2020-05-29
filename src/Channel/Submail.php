<?php

namespace Pqf\Notification\Channel;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Pqf\Notification\Contract\SendMessage;
use Pqf\Notification\NotificationResponse;


class Submail implements SendMessage
{
    public function send($account, $content)
    {
        $config = config('pqfno.channel.submail');
        $sign=config('pqfno.sign','');
        $sign = Str::start($sign, '【');
        $sign = Str::finish($sign, '】');
        if (!Str::contains($content, $sign)) {
            $content=Str::start($content, $sign);
        }
        $content = mb_convert_encoding($content, 'UTF-8', 'UTF-8');
        if(Str::startsWith($account,'+86')===true){
            $account=substr($account,3);
            $host_url='https://api.mysubmail.com/message/send.json';
        }else{
            $host_url='https://api.mysubmail.com/internationalsms/send.json';
            $data['appid']=$config['appid_international'];
            $data['signature']=$config['signature_international'];
        }
        $data['appid'] =$config['appid'];
        $data['signature'] =$config['signature'];
        $data['to'] = $account;
        $data['content'] = $content;
        $client = new Client();
        try{
            $response = $client->post($host_url, [
                'timeout' => 5,
                'http_errors'=>false,
                'json'=>$data,
            ]);
            $ret = json_decode($response->getBody()->getContents(), true);
            if($ret['status']=='success'){
                return new NotificationResponse(NotificationResponse::SUCCESS_CODE , trans('pqfno::sms.send_success'));
            }else{
                Log::info('send_sms_err',['code'=>$ret['code'],'msg'=>$ret['msg']]);
                return new NotificationResponse(NotificationResponse::FAIL_CODE ,trans('pqfno::sms.send_failed'));
            }
        }catch (\Exception $e){
            Log::info('send_sms_err',['code'=>$e->getCode(),'msg'=>$e->getMessage()]);
            return new NotificationResponse(NotificationResponse::FAIL_CODE ,trans('pqfno::sms.send_failed'));
        }
    }
}
