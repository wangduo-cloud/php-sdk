<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 14:40
 */

namespace Shenjian\Internal;

use Shenjian\Http\RequestCore;
use Shenjian\Http\RequestCoreException;
use Shenjian\Http\ResponseCore;
use Shenjian\Core\ShenjianException;

class CommonOperation
{
    //Shenjian 内部常量
    const SHENJIAN_REQUEST_BASE = 'http://www.shenjian.io/rest/v3/';
    const SHENJIAN_CONTROLLER = 'controller';
    const SHENJIAN_ACTION = 'action';
    const SHENJIAN_SUB_ACTION = 'sub_action';
    const SHENJIAN_USER_KEY = 'user_key';
    const SHENJIAN_TIMESTAMP = 'timestamp';
    const SHENJIAN_SIGN = 'sign';
    const SHENJIAN_NAME = 'Shenjian-sdk-php';
    const SHENJIAN_VERSION = '1.0.0';
    const SHENJIAN_APP_ID = 'app_id';

    protected $user_key;
    protected $timestamp;
    protected $sign;

    /**
     * CommonOperation constructor.
     * @param Credentials $credentials
     */
    public function __construct($credentials){
        $this->user_key = $credentials->user_key;
        $this->timestamp = $credentials->timestamp;
        $this->sign = $credentials->sign;
    }

    /**
     * @param string $path
     * @param array $params Key-Value数组
     * @return mixed
     * @throws ShenjianException
     */
    public function doRequest($path, $params = null){
        $request_url = self::SHENJIAN_REQUEST_BASE . $path;
        $request = new RequestCore($request_url);
        $request->set_method('post');
        $params[self::SHENJIAN_USER_KEY] = $this->user_key;
        $params[self::SHENJIAN_TIMESTAMP] = $this->timestamp;
        $params[self::SHENJIAN_SIGN] = $this->sign;
        $request->set_body($params);
        try {
            $request->send_request();
        } catch (RequestCoreException $e) {
            throw(new ShenjianException('RequestCoreException: ' . $e->getMessage()));
        }
        $response_header = $request->get_response_header();
        $response = new ResponseCore($response_header, $request->get_response_body(), $request->get_response_code());
        $body = $response->body;
        if(!$this->isResponseOk($response)){
            $httpStatus = strval($response->status);
            $code = $this->retrieveErrorCode($body);
            $message = $this->retrieveErrorMessage($body);
            $details = array(
                'status' => $httpStatus,
                'code' => $code,
                'message' => $message,
                'body' => $body
            );
            throw new ShenjianException($details);
        }
        return json_decode($body, true);
    }
    
    /**
     * 根据返回http状态码判断，[200-299]即认为是OK
     *
     * @param ResponseCore $response
     * @return bool
     */
    protected function isResponseOk($response)
    {
        $status = $response->status;
        if ((int)(intval($status) / 100) == 2) {
            return true;
        }
        return false;
    }

    /**
     * 尝试从body中获取错误Code
     *
     * @param $body
     * @return string
     */
    private function retrieveErrorCode($body)
    {
        if (empty($body) || false === strpos($body, '<?xml')) {
            return '';
        }
        $xml = simplexml_load_string($body);
        if (isset($xml->Code)) {
            return strval($xml->Code);
        }
        return '';
    }

    /**
     * 尝试从body中获取错误Message
     *
     * @param $body
     * @return string
     */
    private function retrieveErrorMessage($body)
    {
        if (empty($body) || false === strpos($body, '<?xml')) {
            return '';
        }
        $xml = simplexml_load_string($body);
        if (isset($xml->Message)) {
            return strval($xml->Message);
        }
        return '';
    }
}