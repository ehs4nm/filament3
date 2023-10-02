<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitMQService
{
    private $connection;
    private $channel;

    public function __construct()
    {
        // Initialize the connection and channel in the constructor
        $this->connection = new AMQPStreamConnection(
            env('RABBITMQ_HOST'),
            env('RABBITMQ_PORT'),
            env('RABBITMQ_USER'),
            env('RABBITMQ_PASSWORD'),
            env('RABBITMQ_VHOST')
        );

        $this->channel = $this->connection->channel();
    }

    public function publish($message)
    {
        $this->channel->exchange_declare('test_exchange', 'direct', false, false, false);
        $this->channel->queue_declare('test_queue', false, false, false, false);
        $this->channel->queue_bind('test_queue', 'test_exchange', 'test_key');
        $msg = new AMQPMessage($message);
        $this->channel->basic_publish($msg, 'test_exchange', 'test_key');
        echo " [x] Sent $message to test_exchange / test_queue.\n";
    }

    public function consume()
    {
        $callback = function ($msg) {
            echo ' [x] Received ', $msg->body, "\n";
        };
        $this->channel->queue_declare('test_queue', false, false, false, false);
        $this->channel->basic_consume('test_queue', '', false, true, false, false, $callback);
        echo 'Waiting for new message on test_queue', " \n";
        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }

    public function __destruct()
    {
        // Close the channel and connection in the destructor
        $this->channel->close();
        $this->connection->close();
    }
}