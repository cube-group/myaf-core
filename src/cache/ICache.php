<?php
/**
 * Created by PhpStorm.
 * User: linyang
 * Date: 17/3/10
 * Time: 下午2:36
 */

namespace Myaf\Cache;

/**
 * Interface ICache
 * @package Myaf\Cache
 */
interface ICache
{
    /**
     * 限制函数名
     * @return mixed
     */
    public static function limits();

    /**
     * ICache constructor.
     * @param $options array|null 连接配置
     */
    public function configure($options);

    /**
     * 关闭缓存连接.
     * @param $error string
     * @return mixed
     */
    public function close($error = '');
}