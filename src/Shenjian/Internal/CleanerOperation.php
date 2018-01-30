<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 11:17
 */

namespace Shenjian\Internal;


use Shenjian\Model\AppCleaner;

class CleanerOperation extends CommonOperation
{
    const CONTROLLER = "cleaner";

    /**
     * 创建清洗应用
     *
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function create($params){
        $path = self::CONTROLLER . "/create";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 删除清洗应用
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function delete($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/delete";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 修改清洗应用信息
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function edit($app_id, $params){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/edit";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 启动清洗应用
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function start($app_id, $params = null){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/start";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 停止清洗应用
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function stop($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/stop";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 暂停清洗应用
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function pause($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/pause";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 继续清洗应用
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function resume($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/resume";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 获取清洗应用列表
     *
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getList($params = null){
        $path = self::CONTROLLER . "/list";
        $result = $this->doRequest($path, $params);
        if(!is_array($result)){
            $result = json_decode($result, true);
        }
        $result_list = $result['data']['list'];
        foreach ($result_list as $key => $app){
            $app_tmp = new AppCleaner($app['app_id'], $app['info'], $app['name'], $app['type'], $app['status'], $app['time_create']);
            $result_list[$key] = $app_tmp;
        }
        $result['data']['list'] = $result_list;
        return $result;
    }

    /**
     * 获取清洗应用状态
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getStatus($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/status";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 获取清洗应用速率
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getSpeed($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/speed";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 获取清洗应用的数据信息
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getSource($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/source";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 修改清洗应用的运行节点
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function changeNode($app_id, $params = null){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/node";
        $result = $this->doRequest($path, $params);
        return $result;
    }


    /*----------------------- begin config---------------------------*/


    /**
     * 设置清洗应用自定义项
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function configCustom($app_id, $params){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/config/custom";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 设置清洗应用的代理
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function configProxy($app_id, $params){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/config/proxy";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 配置清洗应用的文件云托管
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function configHost($app_id, $params){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/config/host";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 设置清洗应用的输入数据源和输出数据源
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function configSource($app_id, $params){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/config/source";
        $result = $this->doRequest($path, $params);
        return $result;
    }


    /*----------------------- begin webhook---------------------------*/


    /**
     * 设置清洗应用的Webhook
     *
     * @param int $app_id
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function setWebhook($app_id, $params){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/webhook/set";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 删除清洗应用的Webhook
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function deleteWebhook($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/webhook/delete";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 获取清洗应用的Webhook
     *
     * @param int $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getWebhook($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/webhook/get";
        $result = $this->doRequest($path, $params);
        return $result;
    }
}