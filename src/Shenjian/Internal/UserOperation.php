<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 11:14
 */

namespace Shenjian\Internal;


class UserOperation extends CommonOperation
{
    const CONTROLLER = "user";

    /**
     * 获取账号余额
     *
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getMoney(){
        $path = self::CONTROLLER . "/money";
        $result = $this->doRequest($path);
        return $result;
    }

    /**
     * 获取node信息
     *
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getNode(){
        $path = self::CONTROLLER . "/node";
        $result = $this->doRequest($path);
        return $result;
    }
}