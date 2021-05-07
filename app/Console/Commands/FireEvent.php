<?php

namespace App\Console\Commands;

use App\Jobs\CreateOrderJob;
use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FireEvent extends Command
{
    protected $signature = 'fire';

    public function handle()
    {
        $data = [
            'name' => 'Fabio',
            'email' => 'fabio@fabiofarias.com.br',
            'cc' => '9999222233334444',
            'exp' => '23/28'
        ];

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            5672,
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST'));

        $channel = $connection->channel();
        $channel->queue_declare('order', false, true, false, false);
        $channel->exchange_declare('application-showcase', 'direct', false, false, false);

        $msg = new AMQPMessage(json_encode($data));
        $channel->basic_publish($msg, 'application-showcase');

        $channel->close();
        $connection->close();
    }
}
