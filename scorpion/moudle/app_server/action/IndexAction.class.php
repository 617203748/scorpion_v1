<?php
/**
 * 功能说明：<前台首页控制类>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 开发团队：太原锐意鹏达科技有限公司
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class IndexAction extends BaseAction
{

    public function __construct()
    {
        parent::__construct(new IndexModel());

        $this->model = new IndexModel();
    }

    //http://localhost:1235/app_server/index.php
    public function index()
    {
        $this->display('index');
    }

    //http://localhost:1235/app_server/index.php?act=index&m=test_db
    public function test_db()
    {
        $admin = $this->model->testDB();
        echo json_encode($admin);
    }

}