<?php

namespace App\Services;

class MailGunService implements MailInterface
{
    private $template;
    private $tags;
    private $data;

    public function send($subject, $to, $from)
    {
        try {
            $params = [
                'subject' => $subject,
                'to' => $to,
                'from' => $from,
                'template' => $this->template,
                'o:tracking' => true,
                'o:tag' => $this->tags,
                'h:X-Mailgun-Variables' => $this->data
            ];

            $data = [
                'host' => env('MAILGUN_HOST'),
                'domain' =>  env('MAILGUN_DOMAIN'),
                'secret' => env('MAILGUN_SECRET')
            ];

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $data['host'].$data['domain'].'/messages');
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($curl, CURLOPT_USERPWD, "api:".$data['secret']);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
            $result = curl_exec($curl);
            curl_close($curl);
            $resultArray = json_decode($result);

            if ($resultArray->id) {
                return $resultArray->id;
            }

            return false;
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    public function tags(array $data)
    {
        $this->tags = (is_array($data)) ? implode(',', $data) : $data;
    }

    public function data(array $data)
    {
        $this->data = json_encode($data);
    }

    public function template($name)
    {
        $this->template = $name;
    }
}
