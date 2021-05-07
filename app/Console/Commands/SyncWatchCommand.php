<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class SyncWatchCommand extends Command
{

    protected $syncAction;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "sync:watch {queue?}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Watch rabbitmq";


    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $queue = $this->argument('queue');

        if (!$queue) {
            $this->error(chr(10).' Queue not exist');
            return false;
        }

        $exchange = 'application-showcase';
        $exchangeType = AMQPExchangeType::TOPIC;

        $connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            5672,
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST'));

        $ch = $connection->channel();

        $ch->queue_declare($queue, true, true, false, false);
        $ch->exchange_declare($exchange, $exchangeType, true, true);
        $ch->queue_bind($queue, $exchange);

        $ch->basic_qos(null, 10000, null);

        $ch->basic_consume(
            $queue,
            '',
            false,
            false,
            false,
            false,
            [$this, 'processMessage']
        );

        register_shutdown_function([$this, 'shutdown'], $ch, $connection);

        while ($ch->is_consuming()) {
            $ch->wait();
        }

        $ch->close();
        $connection->close();
    }

    public function processMessage(AMQPMessage $message)
    {
        if ($message->body) {
            dump(json_decode($message->body, true));
            $message->ack();
            return;
        }

        $message->nack(true);
    }

    public function shutdown($channel, $connection)
    {
        $channel->close();
        $connection->close();
    }
}
