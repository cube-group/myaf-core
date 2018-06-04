<?php

namespace Myaf\Core;

/**
 * Class ControlWeb.
 * Console Controller基类.
 * (核心类勿改)
 * @package Core
 */
abstract class ConsoleController extends Controller
{
    public function init()
    {
        parent::init();

        if (!$this->req->isCli()) {
            $this->shutdown('<b>not cli</b>', false);
        }
    }

    /**
     * 获取console模式下命令行获取到的参数
     * @return string|int|array
     */
    protected function params()
    {
        return $this->getRequest()->getParam('value');
    }

    /**
     * 直接打印信息
     * @param array|string $value
     */
    public function send($value)
    {
        echo $value . "\n";
    }

    /**
     * 直接打印json信息
     * @param string $data
     * @param string $msg
     * @param int $code
     */
    public function json($data = '', $msg = '', $code = 0)
    {
        echo G::json($data, $msg, $code) . "\n";
    }
}