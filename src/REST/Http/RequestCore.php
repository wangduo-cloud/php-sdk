<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/17
 * Time: 14:11
 */

namespace REST\Http;


class RequestCore
{
    /**
     * The URL being requested.
     */
    public $request_url;

    public $response;


    public function prepRequest(){
        $curl_handle = curl_init();

        curl_setopt($curl_handle, CURLOPT_URL, $this->request_url);

        return $curl_handle;
    }

    public function sendRequest(){
        set_time_limit(0);
        $curl_handle = $this->prepRequest();
        $this->response = curl_exec($curl_handle);



        curl_close($curl_handle);
    }

}