<?php

namespace Shenjian\Core;

/**
 * Class ShenjianException
 *
 * ShenjianClient在使用的时候，所抛出的异常，用户在使用ShenjianClient的时候，要Try住相关代码，
 * try的Exception应该是ShenjianException，其中会得到相关异常原因
 *
 * @package Shenjian\Core
 */
class ShenjianException extends \Exception
{
    private $details = array();

    function __construct($details)
    {
        if (is_array($details)) {
            $message = $details['code'] . ': ' . $details['message'];
            parent::__construct($message);
            $this->details = $details;
        } else {
            $message = $details;
            parent::__construct($message);
        }
    }

    public function getHTTPStatus()
    {
        return isset($this->details['status']) ? $this->details['status'] : '';
    }

    public function getErrorCode()
    {
        return isset($this->details['code']) ? $this->details['code'] : '';
    }

    public function getErrorMessage()
    {
        return isset($this->details['message']) ? $this->details['message'] : '';
    }

    public function getDetails()
    {
        return isset($this->details['body']) ? $this->details['body'] : '';
    }
}
