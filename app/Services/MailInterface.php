<?php


namespace App\Services;

interface MailInterface
{
    public function send($subject, $to, $from);

    public function tags(array $data);

    public function data(array $data);

    public function template($name);
}
