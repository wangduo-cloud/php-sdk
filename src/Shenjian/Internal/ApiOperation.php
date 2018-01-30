<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 11:17
 */

namespace Shenjian\Internal;

use Shenjian\Model\AppApi;

class ApiOperation extends CommonOperation
{
    const CONTROLLER = "api";
    
    /**
     * 创建API
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
     * 删除API
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
     * 修改API
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
     * 获取API的调用key
     *
     * @param $app_id
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getKey($app_id){
        $params[self::SHENJIAN_APP_ID] = $app_id;
        $path = self::CONTROLLER . "/{$app_id}/key";
        $result = $this->doRequest($path, $params);
        return $result;
    }

    /**
     * 获取API列表
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
            $app_tmp = new AppApi($app['app_id'], $app['info'], $app['name'], $app['type'], $app['status'], $app['time_create']);
            $result_list[$key] = $app_tmp;
        }
        $result['data']['list'] = $result_list;
        return $result;
    }


    /*----------------------- begin config---------------------------*/


    /**
     * 配置代理
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
     * 配置文件云托管
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
}