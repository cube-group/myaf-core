<?php

namespace Myaf\MQ;

use Exception;
use Myaf\Cache\LRedis;

/**
 * Class LRedisMQ
 * @package Myaf\MQ
 */
class LRedisMQ implements IMQ
{
    /**
     * @var LRedis
     */
    private $redis;
    /**
     * @var string
     */
    private $queueName = '';

    /**
     * LRedisMQ constructor.
     * @param null $options
     * @param null $redis
     * @throws Exception
     */
    public function __construct($options = null, $redis = null)
    {
        if ($redis && $redis instanceof LRedis) {
            $this->redis = $redis;
        } else {
            if (!$options) {
                throw new Exception('LRedisMQ options is null');
            }
            $this->redis = new LRedis($options);
        }
    }

    /**
     * 生产队列消息.
     * @param $message string 消息内容
     * @param $queue string (rabbit中代表routerKey,redis中代表list的keyName)
     * @return mixed
     */
    public function product($message, $queue)
    {
        return $this->redis->lPush($queue, $message);
    }

    /**
     * 消费队列.
     * @param $queue string (rabbit中代表routerKey,redis中代表list的keyName)
     * @param $count int 消费条数
     * @return mixed
     */
    public function consume($queue, $count = 1)
    {
        $this->queueName = $queue;
        $messages = [];
        for ($i = 0; $i < $count; $i++) {
            if ($item = $this->redis->rPop($queue)) {
                $messages[] = $item;
            }
        }
        return (!$messages || count($messages) > 1) ? $messages : $messages[0];
    }

    /**
     * 消费状态.
     * @param bool $flag
     * @throws Exception
     */
    public function consumeStatus($flag = true)
    {
        throw new Exception('Method Not Allowed');
    }

    public function reQueue($message)
    {
        if ($this->queueName) {
            return $this->product($message, $this->queueName);
        }
        return false;
    }

    /**
     * 关闭连接.
     * @return mixed
     */
    public function close()
    {
        $this->redis->close();
    }
}