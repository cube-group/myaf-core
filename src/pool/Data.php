<?php
/**
 * Created by PhpStorm.
 * User: linyang
 * Date: 2017/11/17
 * Time: 下午5:47
 */

namespace Myaf\Pool;

use Myaf\Core\G;
use Myaf\Session\LRedisSession;
use Exception;
use Myaf\Mongo\LMongo;
use Myaf\Mysql\LDB;
use Myaf\MQ\LHttpRabbitMQ;
use Myaf\MQ\LRabbitMQ;
use Myaf\MQ\LRedisMQ;
use Myaf\Cache\LMemcache;
use Myaf\Cache\LRedis;

/**
 * mysql、mongodb、redis、memcache、redis队列、rabbitmq队列等连接实例
 * All Over The Framework :)
 * Class Data
 * @package Myaf\Pool
 */
class Data
{
    /**
     * 连接队列
     * @var array
     */
    private static $connections = [];

    /**
     * 获取数据库连接操作实例LDB
     * @param $name string
     * @return bool|LDB
     * @throws Exception
     */
    public static function db($name = 'default')
    {
        $conf = G::conf();
        if (!$conf->mysql || !$conf->mysql->$name) {
            throw new Exception("mysql {$name} connection config is null");
        }
        $key = 'mysql-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LDB(
                $conf->mysql->$name->master ? $conf->mysql->$name->master->toArray() : $conf->mysql->$name->toArray(),
                $conf->mysql->$name->slave ? $conf->mysql->$name->slave->toArray() : null
            );
        }
        return self::$connections[$key];
    }

    /**
     * 获取Mongo操作实例
     * @param $name string
     * @return bool|LMongo
     * @throws Exception
     */
    public static function mongo($name = 'default')
    {
        $conf = G::conf();
        if (!$conf->mongo || !$conf->mongo->$name) {
            throw new Exception("mongo {$name} connection config is null");
        }
        $key = 'mongo-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LMongo($conf->mongo->$name->toArray());
        }
        return self::$connections[$key];
    }

    /**
     * 获取Redis连接操作实例LRedis
     * @param $name string
     * @return bool|LRedis
     * @throws Exception
     */
    public static function redis($name = 'default')
    {
        $conf = G::conf();
        if (!$conf->redis || !$conf->redis->$name) {
            throw new Exception("redis {$name} connection config is null");
        }
        $key = 'redis-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LRedis($conf->redis->$name->toArray());
        }
        return self::$connections[$key];
    }

    /**
     * 获取Redis连接操作实例LMemcache
     * @param $name string
     * @return bool|LMemcache
     * @throws Exception
     */
    public static function cache($name = 'default')
    {
        $conf = G::conf();
        if (!$conf->memcache || !$conf->memcache->$name) {
            throw new Exception("memcache {$name} connection config is null");
        }
        $key = 'memcache-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LMemcache($conf->memcache->$name->toArray());
        }
        return self::$connections[$key];
    }

    /**
     * 获取RedisSession操作实例LRedisSession
     * @param string $name
     * @param string $sessionId session_id，如果传了该参数，不会再生成session_id
     * @return LRedisSession
     * @throws Exception
     */
    public static function session($name = 'session', $sessionId = null)
    {
        $conf = G::conf();
        if (!$conf->redis || !$conf->redis->$name) {
            throw new Exception("redis {$name} connection config is null");
        }
        $key = 'session.redis-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LRedisSession(
                $conf->redis->$name->toArray(),
                self::$connections['redis-' . $name],
                $sessionId
            );
        }
        return self::$connections[$key];
    }

    /**
     * 获取LRedisMQ操作实例
     * @param string $name
     * @return LRedisMQ|bool
     * @throws Exception
     */
    public static function mqRedis($name = 'mq')
    {
        $conf = G::conf();
        if (!$conf->redis || !$conf->redis->$name) {
            throw new Exception("mq.redis {$name} connection config is null");
        }
        $key = 'mq.redis-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LRedisMQ(
                $conf->redis->$name->toArray(),
                self::$connections['redis-' . $name]
            );
        }
        return self::$connections[$key];
    }

    /**
     * 获取LHttpRabbitMQ操作实例
     * @param string $name
     * @return LHttpRabbitMQ
     * @throws Exception
     */
    public static function mqHttpRabbit($name = 'default')
    {
        $conf = G::conf();
        if (!$conf->rabbit || !$conf->rabbit->$name) {
            throw new Exception("mq.rabbit {$name} connection config is null");
        }
        $key = 'mq.rabbit.http-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LHttpRabbitMQ($conf->rabbit->$name->toArray());
        }
        return self::$connections[$key];
    }

    /**
     * 获取LRabbitMQ操作实例
     * @param string $name
     * @return LRabbitMQ
     * @throws Exception
     */
    public static function mqRabbit($name = 'default')
    {
        $conf = G::conf();
        if (!$conf->rabbit || !$conf->rabbit->$name) {
            throw new Exception("mq.rabbit {$name} connection config is null");
        }
        $key = 'mq.rabbit.ext-' . $name;
        if (!isset(self::$connections[$key])) {
            self::$connections[$key] = new LRabbitMQ($conf->rabbit->$name->toArray());
        }
        return self::$connections[$key];
    }
}