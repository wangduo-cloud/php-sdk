<?php

require_once __DIR__ . "/Common.php";

use Shenjian\ShenjianClient;
use Shenjian\Core\ShenjianException;
use Shenjian\Model\ProxyType;
use Shenjian\Model\HostType;

$shenjian_client = Common::getShenjianClient();
if (is_null($shenjian_client)) exit(1);

getApiList($shenjian_client);
$app_id = createApi($shenjian_client);
if($app_id <= 0) exit(1);
editApi($shenjian_client, $app_id);
getApiKey($shenjian_client, $app_id);
//deleteApi($shenjian_client, $app_id);

/**
 * 获取API列表
 *
 * @param ShenjianClient $shenjian_client
 */
function getApiList($shenjian_client){
    try{
        $params['page'] = 1;
        $params['page_size'] = 5;
        $api_list = $shenjian_client->getApiList($params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");

    foreach ($api_list as $api){
        Common::println("Api AppId: " . $api->getAppId());
        Common::println("Api Info: " . $api->getInfo());
        Common::println("Api Name: " . $api->getName());
        Common::println("Api Type: " . $api->getType());
        Common::println("Api Status: " . $api->getStatus());
        Common::println("Api TimeCreate: " . $api->getTimeCreate());
        echo "\n";
    }
}

/**
 * 创建API
 *
 * @param ShenjianClient $shenjian_client
 */
function createApi($shenjian_client){
    try{
        $params['app_name'] = "API名称";
        $params['app_info'] = "API信息";
        //API应用代码的base64编码
        $params['code'] = "LyoKICDlrp7ml7bojrflj5bov5E144CBMTDjgIEzMOOAgTYw5pel5Liq6IKh5LiK5qac57uf6K6h5pWw5o2u44CC5YyF5ous5LiK5qac5qyh5pWw44CB57Sv56ev6LSt5Lmw6aKd44CB57Sv56ev5Y2W5Ye66aKd44CB5YeA6aKd44CB5Lmw5YWl5bit5L2N5pWw5ZKM5Y2W5Ye65bit5L2N5pWw44CCCiovCnZhciBkYXlzPSI1IjsvL0BpbnB1dChkYXlzLOe7n+iuoeWRqOacnyw144CBMTDjgIEzMOWSjDYw5pel77yM6buY6K6k5Li6NeaXpSkKCnZhciBjb25maWdzID0gewogICAgZG9tYWluczogWyJmaW5hbmNlLnNpbmEuY29tLmNuIl0sCiAgICBzY2FuVXJsczogW10sCiAgICBmaWVsZHM6IFsgLy8gQVBJ5Y+q5oq95Y+Wc2NhbnVybHPkuK3nmoTnvZHpobXvvIzlubbkuJTkuI3kvJrlho3oh6rliqjlj5HnjrDmlrDpk77mjqUKICAgICAgICB7CiAgICAgICAgICAgIG5hbWU6ICJpdGVtcyIsCiAgICAgICAgICAgIHNlbGVjdG9yOiAiLy90YWJsZVtAaWQ9J2RhdGFUYWJsZSddLy90ciIsIAogICAgICAgICAgICByZXBlYXRlZDogdHJ1ZSwKICAgICAgICAgICAgY2hpbGRyZW46IFsKICAgICAgICAgICAgICB7CiAgICAgICAgICAgICAgICAgIG5hbWU6ICJjb2RlIiwKICAgICAgICAgICAgICAgICAgYWxpYXM6ICLogqHnpajku6PnoIEiLAogICAgICAgICAgICAgICAgICBzZWxlY3RvcjogIi8vdGRbMV0vYS90ZXh0KCkiLCAKICAgICAgICAgICAgICAgICAgcmVxdWlyZWQ6IHRydWUgCiAgICAgICAgICAgICAgfSwKICAgICAgICAgICAgICB7CiAgICAgICAgICAgICAgICAgIG5hbWU6ICJuYW1lIiwKICAgICAgICAgICAgICAgICAgYWxpYXM6ICLogqHnpajlkI3np7AiLAogICAgICAgICAgICAgICAgICBzZWxlY3RvcjogIi8vdGRbMl0vYS90ZXh0KCkiLAogICAgICAgICAgICAgICAgICByZXF1aXJlZDogdHJ1ZSAKICAgICAgICAgICAgICB9LAogICAgICAgICAgICAgIHsKICAgICAgICAgICAgICAgICAgbmFtZTogImNvdW50IiwKICAgICAgICAgICAgICAgICAgYWxpYXM6ICLkuIrmppzmrKHmlbAiLAogICAgICAgICAgICAgICAgICBzZWxlY3RvcjogIi8vdGRbM10iIAogICAgICAgICAgICAgIH0sCiAgICAgICAgICAgICAgewogICAgICAgICAgICAgICAgICBuYW1lOiAiYmFtb3VudCIsCiAgICAgICAgICAgICAgICAgIGFsaWFzOiAi57Sv56ev6LSt5Lmw6aKdKOS4hykiLAogICAgICAgICAgICAgICAgICBzZWxlY3RvcjogIi8vdGRbNF0iIAogICAgICAgICAgICAgIH0sCiAgICAgICAgICAgICAgewogICAgICAgICAgICAgICAgICBuYW1lOiAic2Ftb3VudCIsCiAgICAgICAgICAgICAgICAgIGFsaWFzOiAi57Sv56ev5Y2W5Ye66aKdKOS4hykiLAogICAgICAgICAgICAgICAgICBzZWxlY3RvcjogIi8vdGRbNV0iIAogICAgICAgICAgICAgIH0sCiAgICAgICAgICAgICAgewogICAgICAgICAgICAgICAgICBuYW1lOiAibmV0IiwKICAgICAgICAgICAgICAgICAgYWxpYXM6ICLlh4Dpop0o5LiHKSIsCiAgICAgICAgICAgICAgICAgIHNlbGVjdG9yOiAiLy90ZFs2XSIgCiAgICAgICAgICAgICAgfSwKICAgICAgICAgICAgICB7CiAgICAgICAgICAgICAgICAgIG5hbWU6ICJiY291bnQiLAogICAgICAgICAgICAgICAgICBhbGlhczogIuS5sOWFpeW4reS9jeaVsCIsCiAgICAgICAgICAgICAgICAgIHNlbGVjdG9yOiAiLy90ZFs3XSIgCiAgICAgICAgICAgICAgfSwKICAgICAgICAgICAgICB7CiAgICAgICAgICAgICAgICAgIG5hbWU6ICJzY291bnQiLAogICAgICAgICAgICAgICAgICBhbGlhczogIuWNluWHuuW4reS9jeaVsCIsCiAgICAgICAgICAgICAgICAgIHNlbGVjdG9yOiAiLy90ZFs4XSIgCiAgICAgICAgICAgICAgfQogICAgICAgICAgICBdCiAgICAgICAgfQogICAgXQp9OwoKY29uZmlncy5iZWZvcmVDcmF3bCA9IGZ1bmN0aW9uKHNpdGUpewogICAgaWYoZGF5cyE9PSI1IiAmJiBkYXlzIT09IjEwIiAmJiBkYXlzIT09IjMwIiAmJiBkYXlzIT09IjYwIil7CiAgICAgIHN5c3RlbS5leGl0KCLovpPlhaXnmoTnu5/orqHlkajmnJ/plJnor6/jgIIiKTsgLy8g5YGc5q2i6LCD55So77yM6L+U5Zue6Ieq5a6a5LmJ6ZSZ6K+v5L+h5oGvCiAgICB9CiAgICAvLyDmoLnmja7ovpPlhaXlgLznlJ/miJDopoHop6PmnpDnmoTnvZHpobV1cmzvvIzlubbmt7vliqDliLBzY2FudXJs5LitCiAgICB2YXIgdXJsID0gImh0dHA6Ly92aXAuc3RvY2suZmluYW5jZS5zaW5hLmNvbS5jbi9xL2dvLnBocC92TEhCRGF0YS9raW5kL2dndGovaW5kZXgucGh0bWw/bGFzdD0iK2RheXMrIiZwPTEiOwogICAgc2l0ZS5hZGRTY2FuVXJsKHVybCk7Cn07Cgp2YXIgZmV0Y2hlciA9IG5ldyBGZXRjaGVyKGNvbmZpZ3MpOwpmZXRjaGVyLnN0YXJ0KCk7Cg==";
        $api = $shenjian_client->createApi($params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("API AppId: " . $api->getAppId());
    Common::println("API Name: " . $api->getName());
    Common::println("API Status: " . $api->getStatus());
    Common::println("API TimeCreate: " . $api->getTimeCreate());
    return $api->getAppId();
}

/**
 * 删除API
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function deleteApi($shenjian_client, $app_id){
    try{
        $shenjian_client->deleteApi($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 修改API信息
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function editApi($shenjian_client, $app_id){
    try{
        $params['app_name'] = "设置的API名称";//不设置则不修改
        $params['app_info'] = "设置的API信息";//不设置则不修改
        $shenjian_client->editApi($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 配置代理
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function configApiProxy($shenjian_client, $app_id){
    try{
        $params['proxy_type'] = ProxyType::BASIC;//代理IP类型
        $shenjian_client->configApiProxy($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 配置文件云托管
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function configApiHost($shenjian_client, $app_id){
    try{
        $params['proxy_type'] = HostType::SHENJIANSHOU;//托管类型
        $params['image'] = true;//是否托管图片类型的文件，true和非零数字都表示托管，不传表示不托管
        $params['text'] = true;//是否托管文本类型的文件，值同上
        $params['audio'] = true;//是否托管文本类型的文件，值同上
        $params['video'] = true;//是否托管文本类型的文件，值同上
        $params['application'] = true;//是否托管文本类型的文件，值同上
        $shenjian_client->configApiHost($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 获取API的调用key
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function getApiKey($shenjian_client, $app_id){
    try{
        $api_key = $shenjian_client->getApiKey($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("API Key: {$api_key}");
}