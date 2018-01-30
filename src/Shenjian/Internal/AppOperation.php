<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/22
 * Time: 13:56
 */

namespace Shenjian\Internal;


use Shenjian\Model\App;

class AppOperation extends CommonOperation
{
    const CONTROLLER = 'app';
    
    /**
     * 获取应用列表
     *
     * @param array $params Key-Value数组
     * @return mixed
     * @throws \Shenjian\Core\ShenjianException
     */
    public function getAppList($params = null){
        $path = self::CONTROLLER . "/list";
        $result = $this->doRequest($path, $params);
        if(!is_array($result)){
            $result = json_decode($result, true);
        }
        $result_list = $result['data']['list'];
        foreach ($result_list as $key => $app){
            $app_tmp = new App($app['app_id'], $app['info'], $app['name'], $app['type'], $app['status'], $app['time_create']);
            $result_list[$key] = $app_tmp;
        }
        $result['data']['list'] = $result_list;
        return $result;
    }

}