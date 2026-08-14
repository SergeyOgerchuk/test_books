<?php

namespace app\services;

interface SmsSenderInterface
{
    public function send(string $phone, string $message): bool;
}
