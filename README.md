#介绍

<h1 align="center"> notification support email or phone </h1>
<p align="center">A tool used send notification support email or phone.</p>

## 安装依赖

```shell
$ composer require pianqifei/notification
```
##发布资源 配置文件 翻译文件(验证码消息默认模板)
`php artisan vendor:publish --provider="Pqf\Notification\PqfNotificationServiceProvider"` 
## 快捷引用
use Pqf\Notification\Facades\PqfNotification 门面方法
##具体逻辑 Pqf\Notification\Notification ##
---
- 发送验证码
- `PqfNotification::chanel('phone')->sendVerifyCode($account,$code=null,$type=1)`
- 验证码有效性验证
- `PqfNotification::chanel('phone')->checkVerifyCode($account,$code=null,$type=1)`
- 发送消息
- `PqfNotification::chanel('phone')->sendSms($account,$content)`
---
## 返回 Pqf\Notification\NotificationResponse实例



