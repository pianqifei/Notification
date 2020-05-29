<?php

namespace Pqf\Notification\Contract;


interface SendMessage
{
    public function send($account, $content);
}
