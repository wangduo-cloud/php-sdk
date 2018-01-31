<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 10:46
 */

namespace Shenjian;

use Shenjian\Core\ShenjianException;
use Shenjian\Internal\Credentials;
use Shenjian\Internal\UserOperation;
use Shenjian\Internal\AppOperation;
use Shenjian\Internal\CrawlerOperation;
use Shenjian\Internal\CleanerOperation;
use Shenjian\Internal\ApiOperation;



class ShenjianClient
{
    private $credentials;
    private $user_operation;
    private $app_operation;
    private $crawler_operation;
    private $api_operation;


    public function __construct($user_key, $user_secret){
        $user_key = trim($user_key);
        $user_secret = trim($user_secret);
        if (empty($user_key)) {
            throw new ShenjianException("access key id is empty");
        }
        if (empty($user_secret)) {
            throw new ShenjianException("access key secret is empty");
        }
        $this->credentials = new Credentials($user_key, $user_secret);
        $this->user_operation = new UserOperation($this->credentials);
        $this->app_operation = new AppOperation($this->credentials);
        $this->crawler_operation = new CrawlerOperation($this->credentials);
        $this->api_operation = new ApiOperation($this->credentials);

        self::checkEnv();
    }

    /**
     * 用来检查sdk需要用的扩展是否打开
     *
     * @throws ShenjianException
     */
    public static function checkEnv()
    {
        if (function_exists('get_loaded_extensions')) {
            //检测curl扩展
            $enabled_extension = array("curl");
            $extensions = get_loaded_extensions();
            if ($extensions) {
                foreach ($enabled_extension as $item) {
                    if (!in_array($item, $extensions)) {
                        throw new ShenjianException("Extension {" . $item . "} is not installed or not enabled, please check your php env.");
                    }
                }
            } else {
                throw new ShenjianException("function get_loaded_extensions not found.");
            }
        } else {
            throw new ShenjianException('Function get_loaded_extensions has been disabled, please check php config.');
        }
    }


    /*----------------------- begin user---------------------------*/


    /**
     * 获取账号余额
     *
     * @return mixed
     */
    public function getMoney(){
        return $this->user_operation->getMoney();
    }

    /**
     * 获取node信息
     *
     * @return mixed
     */
    public function getNode(){
        return $this->user_operation->getNode();
    }


    /*----------------------- begin app---------------------------*/


    /**
     * 获取应用列表
     *
     * @param array $params
     * @return mixed
     */
    public function getAppList($params = null){
        return $this->app_operation->getAppList($params);
    }


    /*----------------------- begin crawler---------------------------*/


    /**
     * 创建爬虫应用
     *
     * @param array $params
     * @return mixed
     */
    public function createCrawler($params){
        return $this->crawler_operation->create($params);
    }

    /**
     * 删除爬虫应用
     *
     * @param int $app_id
     * @return mixed
     */
    public function deleteCrawler($app_id){
        return $this->crawler_operation->delete($app_id);
    }

    /**
     * 修改爬虫应用信息
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function editCrawler($app_id, $params){
        return $this->crawler_operation->edit($app_id, $params);
    }

    /**
     * 启动爬虫应用
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function startCrawler($app_id, $params = null){
        return $this->crawler_operation->start($app_id, $params);
    }

    /**
     * 停止爬虫应用
     *
     * @param int $app_id
     * @return mixed
     */
    public function stopCrawler($app_id){
        return $this->crawler_operation->stop($app_id);
    }

    /**
     * 暂停爬虫应用
     *
     * @param int $app_id
     * @return mixed
     */
    public function pauseCrawler($app_id){
        return $this->crawler_operation->pause($app_id);
    }

    /**
     * 继续爬虫应用
     *
     * @param int $app_id
     * @return mixed
     */
    public function resumeCrawler($app_id){
        return $this->crawler_operation->resume($app_id);
    }

    /**
     * 获取爬虫应用列表
     *
     * @param null $params
     * @return mixed
     */
    public function getCrawlerList($params = null){
        return $this->crawler_operation->getList($params);
    }

    /**
     * 获取爬虫应用的状态
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCrawlerStatus($app_id){
        return $this->crawler_operation->getStatus($app_id);
    }

    /**
     * 获取爬虫应用的速率
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCrawlerSpeed($app_id){
        return $this->crawler_operation->getSpeed($app_id);
    }

    /**
     * 获取爬虫应用的数据信息
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCrawlerSource($app_id){
        return $this->crawler_operation->getSource($app_id);
    }

    /**
     * 修改爬虫应用的运行节点
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function changeCrawlerNode($app_id, $params = null){
        return $this->crawler_operation->changeNode($app_id, $params);
    }

    /**
     * 设置爬虫应用的自定义项
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCrawlerCustom($app_id, $params){
        return $this->crawler_operation->configCustom($app_id, $params);
    }

    /**
     * 设置爬虫应用的代理
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCrawlerProxy($app_id, $params){
        return $this->crawler_operation->configProxy($app_id, $params);
    }

    /**
     * 设置爬虫应用的托管
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCrawlerHost($app_id, $params){
        return $this->crawler_operation->configHost($app_id, $params);
    }

    /**
     * 设置是否打印所有爬虫应用的日志
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCrawlerLog($app_id, $params = null){
        return $this->crawler_operation->configLog($app_id, $params);
    }

    /**
     * 开启爬虫应用的自动发布
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function startCrawlerPublish($app_id, $params){
        return $this->crawler_operation->startPublish($app_id, $params);
    }

    /**
     * 停止爬虫应用的自动发布
     *
     * @param int $app_id
     * @return mixed
     */
    public function stopCrawlerPublish($app_id){
        return $this->crawler_operation->stopPublish($app_id);
    }

    /**
     * 获取爬虫应用的自动发布状态
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCrawlerPublishStatus($app_id){
        return $this->crawler_operation->getPublishStatus($app_id);
    }

    /**
     * 修改爬虫应用的Webhook
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function setCrawlerWebhook($app_id, $params){
        return $this->crawler_operation->setWebhook($app_id, $params);
    }

    /**
     * 删除爬虫应用的Webhook
     *
     * @param int $app_id
     * @return mixed
     */
    public function deleteCrawleWebhook($app_id){
        $crawler_operation = new CrawlerOperation($this->credentials);
        return $crawler_operation->deleteWebhook($app_id);
    }

    /**
     * 获取爬虫应用的Webhook设置
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCrawlerWebhook($app_id){
        $crawler_operation = new CrawlerOperation($this->credentials);
        return $crawler_operation->getWebhook($app_id);
    }


    /*----------------------- begin cleaner---------------------------*/


    /**
     * 创建清洗应用
     *
     * @param array $params
     * @return mixed
     */
    public function createCleaner($params){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->create($params);
    }

    /**
     * 删除清洗应用
     * 
     * @param int $app_id
     * @return mixed
     */
    public function deleteCleaner($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->delete($app_id);
    }

    /**
     * 修改清洗应用信息
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function editCleaner($app_id, $params){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->edit($app_id, $params);
    }

    /**
     * 启动清洗应用
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function startCleaner($app_id, $params = null){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->start($app_id, $params);
    }

    /**
     * 停止清洗应用
     *
     * @param int $app_id
     * @return mixed
     */
    public function stopCleaner($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->stop($app_id);
    }

    /**
     * 暂停清洗应用
     *
     * @param int $app_id
     * @return mixed
     */
    public function pauseCleaner($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->pause($app_id);
    }

    /**
     * 继续清洗应用
     *
     * @param int $app_id
     * @return mixed
     */
    public function resumeCleaner($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->resume($app_id);
    }

    /**
     * 获取清洗应用列表
     *
     * @param array $params
     * @return mixed
     */
    public function getCleanerList($params = null){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->getList($params);
    }

    /**
     * 获取清洗应用状态
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCleanerStatus($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->getStatus($app_id);
    }

    /**
     * 获取清洗应用速率
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCleanerSpeed($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->getSpeed($app_id);
    }

    /**
     * 获取清洗应用的数据信息
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCleanerSource($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->getSource($app_id);
    }

    /**
     * 修改清洗应用的运行节点
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function changeCleanerNode($app_id, $params = null){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->changeNode($app_id, $params);
    }

    /**
     * 设置清洗应用自定义项
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCleanerCustom($app_id, $params){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->configCustom($app_id, $params);
    }

    /**
     * 设置清洗应用的代理
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCleanerProxy($app_id, $params){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->configProxy($app_id, $params);
    }

    /**
     * 配置清洗应用的文件云托管
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCleanerHost($app_id, $params){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->configHost($app_id, $params);
    }

    /**
     * 设置清洗应用的输入数据源和输出数据源
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configCleanerSource($app_id, $params){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->configSource($app_id, $params);
    }

    /**
     * 设置清洗应用的Webhook
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function setCleanerWebhook($app_id, $params){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->setWebhook($app_id, $params);
    }

    /**
     * 删除清洗应用的Webhook
     *
     * @param int $app_id
     * @return mixed
     */
    public function deleteCleanerWebhook($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->deleteWebhook($app_id);
    }

    /**
     * 获取清洗应用的Webhook
     *
     * @param int $app_id
     * @return mixed
     */
    public function getCleanerWebhook($app_id){
        $cleaner_operation = new CleanerOperation($this->credentials);
        return $cleaner_operation->getWebhook($app_id);
    }


    /*----------------------- begin api---------------------------*/


    /**
     * 创建API
     *
     * @param array $params
     * @return mixed
     */
    public function createApi($params){
        $api_operation = new ApiOperation($this->credentials);
        return $api_operation->create($params);
    }

    /**
     * 删除API
     *
     * @param int $app_id
     * @return mixed
     */
    public function deleteApi($app_id){
        $api_operation = new ApiOperation($this->credentials);
        return $api_operation->delete($app_id);
    }

    /**
     * 修改API
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function editApi($app_id, $params){
        $api_operation = new ApiOperation($this->credentials);
        return $api_operation->edit($app_id, $params);
    }

    /**
     * 获取API的调用key
     *
     * @param int $app_id
     * @return mixed
     */
    public function getApiKey($app_id){
        $api_operation = new ApiOperation($this->credentials);
        return $api_operation->getKey($app_id);
    }

    /**
     * 获取API列表
     *
     * @param array $params
     * @return mixed
     */
    public function getApiList($params = null){
        $api_operation = new ApiOperation($this->credentials);
        return $api_operation->getList($params);
    }

    /**
     * 配置代理
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configApiProxy($app_id, $params){
        $api_operation = new ApiOperation($this->credentials);
        return $api_operation->configProxy($app_id, $params);
    }

    /**
     * 配置文件云托管
     *
     * @param int $app_id
     * @param array $params
     * @return mixed
     */
    public function configApiHost($app_id, $params){
        $api_operation = new ApiOperation($this->credentials);
        return $api_operation->configHost($app_id, $params);
    }
}