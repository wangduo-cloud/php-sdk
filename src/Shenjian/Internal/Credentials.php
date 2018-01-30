<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 11:16
 */

namespace Shenjian\Internal;


class Credentials
{
    public $user_key;
    public $user_secret;
    public $timestamp;
    public $sign;

    /**
     * Credentials constructor
     * @param string $user_key
     * @param string $user_secret
     */
    public function __construct($user_key, $user_secret){
        $timestamp = time();
        $sign = strtolower(md5($user_key.$timestamp.$user_secret));
        $this->user_key  = $user_key;
        $this->timestamp = $timestamp;
        $this->sign = $sign;
    }
}