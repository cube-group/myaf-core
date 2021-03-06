<?php
/**
 * Created by PhpStorm.
 * User: linyang
 * Date: 17/7/10
 * Time: 下午2:25
 */

namespace Myaf\Session;

/**
 * Class Session
 * Session基类
 * @package Myaf\Session
 */
abstract class Session implements ISession
{
    /**
     * sessionId
     * @var mixed|string
     */
    protected $sessionId = '';
    /**
     * cookie session name
     * @var mixed|string
     */
    protected $sessionName = 'BASE_PHPSESSID';
    /**
     * session used time.
     * default value is one day.
     * @var int|mixed
     */
    protected $sessionTimeout = 86400;

    /**
     * 设置session过期时间(单位:秒)
     * @param $value
     * @return $this
     */
    public function setTimeout($value)
    {
        return $this;
    }

    /**
     * 设置session值
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value)
    {
        return true;
    }

    /**
     * 一次设置多个值
     * @param $values
     * @return bool
     */
    public function mSet($values)
    {
        if (!$values || !is_array($values)) {
            return false;
        }
        foreach ($values as $key => $value) {
            $this->set($key, $value);
        }
        return true;
    }

    /**
     * 获取session.
     * @param $key string
     * @return mixed
     */
    public function get($key = '')
    {
        return '';
    }

    /**
     * 获取cookie中的定义的session key name.
     * @return mixed
     */
    public function getSessionName()
    {
        return '';
    }

    /**
     * 获取cookie中的sessionName的值
     * 如:PHP_REDIS_SESSION = !@$#$#$^$%^&%$^&$%&$%^
     * @return mixed
     */
    public function getSessionID()
    {
        return '';
    }

    /**
     * 获取session的过期时间(单位:秒)
     * @return mixed
     */
    public function getTTL()
    {
        return 0;
    }

    /**
     * 重置session的过期时间.
     * @return bool
     */
    public function resetTTL()
    {
        return true;
    }

    /**
     * 删除session中的某个key.
     * @param $key
     * @return bool
     */
    public function delete($key)
    {
        return true;
    }

    /**
     * 清除session.
     * @return bool
     */
    public function clear()
    {
        return true;
    }
}