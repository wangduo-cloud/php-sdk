<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2018/1/30
 * Time: 9:56
 */

namespace Shenjian\Model;


class AppCrawler
{
    private $app_id;
    private $info;
    private $name;
    private $type;
    private $status;
    private $time_create;

    public function __construct($app_id, $info, $name, $type, $status, $time_create)
    {
        $this->app_id = $app_id;
        $this->info = $info;
        $this->name = $name;
        $this->type = $type;
        $this->status = $status;
        $this->time_create = $time_create;
    }

}