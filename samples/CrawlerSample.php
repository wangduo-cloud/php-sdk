<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/2/2
 * Time: 15:02
 */
require_once __DIR__ . "/Common.php";

use Shenjian\ShenjianClient;
use Shenjian\Core\ShenjianException;
use Shenjian\Model\ProxyType;
use Shenjian\Model\HostType;
use Shenjian\Model\AppStatus;


$shenjian_client = Common::getShenjianClient();
if (is_null($shenjian_client)) exit(1);

getCrawlerList($shenjian_client);
$app_id = createCrawler($shenjian_client);
if ($app_id <= 0) exit(1);
editCrawler($shenjian_client, $app_id);
$status = startCrawler($shenjian_client, $app_id);
while($status != AppStatus::RUNNING){
    sleep(3);
    $status = getCrawlerStatus($shenjian_client, $app_id);
}
getCrawlerSpeed($shenjian_client, $app_id);
$source_count = 0;
while($source_count <= 0){
    sleep(3);
    $source_count = getCrawlerSource($shenjian_client, $app_id);
}
$status = pauseCrawler($shenjian_client,$app_id);
while($status != AppStatus::PAUSED){
    sleep(3);
    $status = getCrawlerStatus($shenjian_client, $app_id);
}
$status = resumeCrawler($shenjian_client,$app_id);
while($status != AppStatus::RUNNING){
    sleep(3);
    $status = getCrawlerStatus($shenjian_client, $app_id);
}
$status = stopCrawler($shenjian_client, $app_id);
while($status != AppStatus::STOPPED){
    sleep(3);
    $status = getCrawlerStatus($shenjian_client, $app_id);
}
//deleteCrawler($shenjian_client, $app_id);

/**
 * 获取Crawler列表
 *
 * @param ShenjianClient $shenjian_client
 */
function getCrawlerList($shenjian_client){
    try{
        $params['page'] = 1;
        $params['page_size'] = 5;
        $crawler_list = $shenjian_client->getCrawlerList($params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");

    foreach ($crawler_list as $crawler){
        Common::println("Crawler AppId: " . $crawler->getAppId());
        Common::println("Crawler Info: " . $crawler->getInfo());
        Common::println("Crawler Name: " . $crawler->getName());
        Common::println("Crawler Type: " . $crawler->getType());
        Common::println("Crawler Status: " . $crawler->getStatus());
        Common::println("Crawler TimeCreate: " . $crawler->getTimeCreate());
        echo "\n";
    }
}

/**
 * 创建爬虫
 *
 * @param ShenjianClient $shenjian_client
 */
function createCrawler($shenjian_client){
    try{
        $params['app_name'] = "爬虫名称";
        $params['app_info'] = "爬虫信息";
        //爬虫应用代码的base64编码
        $params['code'] = "LyoKICDniKzlj5bosYzosYbojZrlronljZPmuLjmiI/mjpLooYzniYjvvIhodHRwOi8vd3d3LndhbmRvdWppYS5jb20vdG9wL2dhbWXvvInkuIrnmoTmuLjmiI/kv6Hmga/jgIIKICDlm6DkuLrliJfooajpobXmmK9qc+WKqOaAgeeUn+aIkOeahO+8iOe9kemhtea6kOeggeS4reW5tuayoeacie+8ie+8jOaJgOS7pemcgOimgeWIhuaekOe9kee7nOivt+axgu+8jOaJi+WKqOa3u+WKoOS4i+S4gOmhteWIl+ihqOmhteWSjOWGheWuuemhtemTvuaOpeWIsOW+heeIrOmYn+WIl+S4reOAggoqLwp2YXIgY29uZmlncyA9IHsKICAgIGRvbWFpbnM6IFsid2FuZG91amlhLmNvbSJdLAogICAgc2NhblVybHM6IFsiaHR0cDovL2FwcHMud2FuZG91amlhLmNvbS9hcGkvdjEvYXBwcz90eXBlPXdlZWtseXRvcGdhbWUmbWF4PTEyJnN0YXJ0PTAiXSwKICAgIGNvbnRlbnRVcmxSZWdleGVzOiBbL2h0dHA6XC9cL3d3d1wud2FuZG91amlhXC5jb21cL2FwcHNcLy4qL10sCiAgICBoZWxwZXJVcmxSZWdleGVzOiBbL2h0dHA6XC9cL2FwcHNcLndhbmRvdWppYVwuY29tXC9hcGlcL3YxXC9hcHBzXD90eXBlPXdlZWtseXRvcGdhbWUmbWF4PTEyJnN0YXJ0PVxkKy9dLAogICAgZmllbGRzOiBbCiAgICAgICAgewogICAgICAgICAgICBuYW1lOiAiZ2FtZV9uYW1lIiwKICAgICAgICAgICAgYWxpYXM6ICLmuLjmiI/lkI0iLAogICAgICAgICAgICBzZWxlY3RvcjogIi8vc3Bhbltjb250YWlucyhAY2xhc3MsJ3RpdGxlJyldIiwKICAgICAgICAgICAgcmVxdWlyZWQ6IHRydWUgCiAgICAgICAgfSwKICAgICAgICB7CiAgICAgICAgICAgIG5hbWU6ICJnYW1lX2Rvd25sb2FkIiwKICAgICAgICAgICAgYWxpYXM6ICLkuIvovb3ph48iLAogICAgICAgICAgICBzZWxlY3RvcjogIi8vaVtAaXRlbXByb3A9J2ludGVyYWN0aW9uQ291bnQnXSIKICAgICAgICB9LAogICAgICAgIHsKICAgICAgICAgICAgbmFtZToiZ2FtZV9pY29uIiwKICAgICAgICAgICAgYWxpYXM6ICLmuLjmiI/lm77moIciLAogICAgICAgICAgICBzZWxlY3RvcjoiLy9kaXZbY29udGFpbnMoQGNsYXNzLCdhcHAtaWNvbicpXS9pbWdbQGl0ZW1wcm9wPSdpbWFnZSddL0BzcmMiCiAgICAgICAgfQogICAgICAgIAogICAgXQp9OwoKLyoKICDlm57osIPlh73mlbBvblByb2Nlc3NIZWxwZXJVcmzvvJrojrflj5bkuIvkuIDpobXliJfooajpobXku6Xlj4rku47liJfooajpobXkuK3ojrflj5blhoXlrrnpobXpk77mjqXvvIzlubbmiYvliqjmt7vliqDliLDlvoXniKzpmJ/liJfkuK0KKi8KY29uZmlncy5vblByb2Nlc3NIZWxwZXJVcmwgPSBmdW5jdGlvbih1cmwsIGNvbnRlbnQsIHNpdGUpIHsKICAgIC8vIOWIl+ihqOmhtei/lOWbnueahOaVsOaNruaYr2pzb27vvIzpnIDopoHlhYjovazmjaLmiJBqc29u5qC85byPCiAgICB2YXIgamFyciA9IEpTT04ucGFyc2UoY29udGVudCk7CiAgICAvLyDku45qc29u5pWw57uE5Lit6I635Y+W5YaF5a656aG16ZO+5o6l5bm25re75Yqg5Yiw5b6F54is6Zif5YiX5LitCiAgICBmb3IgKHZhciBpID0gMCwgbiA9IGphcnIubGVuZ3RoOyBpIDwgbjsgaSsrKSB7CiAgICAgIHZhciBuZXdfdXJsID0gImh0dHA6Ly93d3cud2FuZG91amlhLmNvbS9hcHBzLyIramFycltpXS5wYWNrYWdlTmFtZTsKICAgICAgc2l0ZS5hZGRVcmwobmV3X3VybCk7CiAgICB9CiAgICAvLyDojrflj5bkuIvkuIDpobXliJfooajpobXpk77mjqXlubbmt7vliqDliLDlvoXniKzpmJ/liJfkuK0KICAgIHZhciBjdXJyZW50U3RhcnQgPSBwYXJzZUludCh1cmwuc3Vic3RyaW5nKHVybC5pbmRleE9mKCImc3RhcnQ9IikgKyA3KSk7CiAgICB2YXIgc3RhcnQgPSBjdXJyZW50U3RhcnQrMTI7CiAgICBpZihzdGFydCA8IDEwMCl7IC8vIOivpWRlbW/lj6rniKzlj5bmuLjmiI/mjpLooYzmppzliY0xMDDnmoTmuLjmiI8KICAgICAgc2l0ZS5hZGRVcmwoImh0dHA6Ly9hcHBzLndhbmRvdWppYS5jb20vYXBpL3YxL2FwcHM/dHlwZT13ZWVrbHl0b3BnYW1lJm1heD0xMiZzdGFydD0iK3N0YXJ0KTsKICAgIH0KICAgIHJldHVybiBmYWxzZTsgLy8g6L+U5ZueZmFsc2XooajnpLrkuI3ku47lvZPliY3liJfooajpobXkuK3oh6rliqjlj5HnjrDmlrDnmoTpk77mjqXvvIzku47ogIzpgb/lhY3mt7vliqDml6DnlKjnmoTpk77mjqXvvIzmj5Dpq5jniKzlj5bpgJ/luqYKfTsKCnZhciBjcmF3bGVyID0gbmV3IENyYXdsZXIoY29uZmlncyk7CmNyYXdsZXIuc3RhcnQoKTsK";
        $crawler = $shenjian_client->createCrawler($params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Crawler AppId: " . $crawler->getAppId());
    Common::println("Crawler Name: " . $crawler->getName());
    Common::println("Crawler Status: " . $crawler->getStatus());
    Common::println("Crawler TimeCreate: " . $crawler->getTimeCreate());
    return $crawler->getAppId();
}

/**
 * 删除爬虫
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function deleteCrawler($shenjian_client, $app_id){
    try{
        $shenjian_client->deleteCrawler($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 修改爬虫信息
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function editCrawler($shenjian_client, $app_id){
    try{
        $params['app_name'] = "设置的爬虫名称";//不设置则不修改
        $params['app_info'] = "设置的爬虫信息";//不设置则不修改
        $shenjian_client->editCrawler($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 启动爬虫
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function startCrawler($shenjian_client, $app_id){
    try{
        $status = $shenjian_client->startCrawler($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Crawler Status : " . $status);
    return $status;
}

/**
 * 停止爬虫
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function stopCrawler($shenjian_client, $app_id){
    try{
        $status = $shenjian_client->stopCrawler($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Crawler Status : " . $status);
    return $status;
}

/**
 * 暂停爬虫
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function pauseCrawler($shenjian_client, $app_id){
    try{
        $status = $shenjian_client->pauseCrawler($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Crawler Status : " . $status);
    return $status;
}

/**
 * 继续爬虫
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function resumeCrawler($shenjian_client, $app_id){
    try{
        $status = $shenjian_client->resumeCrawler($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Crawler Status : " . $status);
    return $status;
}

/**
 * 获取爬虫状态
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function getCrawlerStatus($shenjian_client, $app_id){
    try{
        $status = $shenjian_client->getCrawlerStatus($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Crawler Status : " . $status);
    return $status;
}

/**
 * 获取爬虫速率
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function getCrawlerSpeed($shenjian_client, $app_id){
    try{
        $speed = $shenjian_client->getCrawlerSpeed($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Crawler Speed : " . $speed);
}

/**
 * 修改爬虫应用的运行节点
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function changeCrawlerNode($shenjian_client, $app_id){
    try{
        $params['node_delta'] = 1;
        $node = $shenjian_client->changeCrawlerNode($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Node Running : " . $node->getNodeRunning());
    Common::println("Node Left : " . $node->getNodeLeft());
}

/**
 * 获取爬虫应用的数据信息
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function getCrawlerSource($shenjian_client, $app_id){
    try{
        $source = $shenjian_client->getCrawlerSource($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Source AppId: " . $source->getAppId());
    Common::println("Source Type: " . $source->getType());
    Common::println("Source Count: " . $source->getCount());
    return $source->getCount();
}

/**
 * 清空爬虫数据
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function clearCrawlerSource($shenjian_client, $app_id){
    try{
        $shenjian_client->clearCrawlerSource($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 删除爬虫数据
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function deleteCrawlerSource($shenjian_client, $app_id){
    try{
        $params['days'] = 1;//删除多少天前的数据，无默认值，最小为1
        $shenjian_client->deleteCrawlerSource($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 设置爬虫应用的代理
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function configCrawlerProxy($shenjian_client, $app_id){
    try{
        $params['proxy_type'] = ProxyType::BASIC;//代理IP类型
        $shenjian_client->configCrawlerProxy($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 设置爬虫应用的托管
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function configCrawlerHost($shenjian_client, $app_id){
    try{
        $params['proxy_type'] = HostType::SHENJIANSHOU;//托管类型
        $params['image'] = true;//是否托管图片类型的文件，true和非零数字都表示托管，不传表示不托管
        $params['text'] = true;//是否托管文本类型的文件，值同上
        $params['audio'] = true;//是否托管文本类型的文件，值同上
        $params['video'] = true;//是否托管文本类型的文件，值同上
        $params['application'] = true;//是否托管文本类型的文件，值同上
        $shenjian_client->configCrawlerHost($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}


/**
 * 获取爬虫应用的Webhook设置
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function getCrawlerWebhook($shenjian_client, $app_id){
    try{
        $webhook = $shenjian_client->getCrawlerWebhook($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Cleaner Webhook Url: " . $webhook->getUrl());
    Common::println("Cleaner Webhook Events: " . json_encode($webhook->getEvents()));
}


/**
 * 删除爬虫应用的Webhook
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function deleteCrawleWebhook($shenjian_client, $app_id){
    try{
        $shenjian_client->deleteCrawleWebhook($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 修改爬虫应用的Webhook
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function setCrawlerWebhook($shenjian_client, $app_id){
    try{
        $params['url'] = "http://www.example.com";//webhook的通知地址，需要是能外网访问的地址
        $params['data_new'] = true;//新增数据是否发送webhook，true和非零数字都表示发送，不传表示不发送
        $params['data_updated'] = true;//变动数据是否发送webhook，值同上
        $params['msg_custom'] = true;//自定义消息是否发送webhook，值同上
        $shenjian_client->setCrawlerWebhook($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 获取爬虫应用的自动发布状态
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function getCrawlerPublishStatus($shenjian_client, $app_id){
    try{
        $publish_status = $shenjian_client->getCrawlerPublishStatus($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
    Common::println("Publish Status: " . $publish_status);
}

/**
 * 开启爬虫应用的自动发布
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function startCrawlerPublish($shenjian_client, $app_id){
    try{
        $params['publish_id'] = ["发布项目的Id", "发布项目的Id"];//发布项ID（发布项目前只能通过网页创建，暂时不开放通过接口创建）
        $shenjian_client->startCrawlerPublish($app_id, $params);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}

/**
 * 停止爬虫应用的自动发布
 *
 * @param ShenjianClient $shenjian_client
 * @param $app_id
 */
function stopCrawlerPublish($shenjian_client, $app_id){
    try{
        $shenjian_client->stopCrawlerPublish($app_id);
    }catch (ShenjianException $e){
        Common::println(__FUNCTION__ . ": FAILED");
        Common::println($e->getMessage());
        return;
    }
    Common::println(__FUNCTION__ . ": OK");
}