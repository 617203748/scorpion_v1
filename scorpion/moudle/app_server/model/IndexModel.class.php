<?php
/**
 * 功能说明：<公司系统设置表模型类>
 * ============================================================================
 * 版权所有：北京北门外科技有限公司
 * ----------------------------------------------------------------------------
 * 作者：维真技术部
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class IndexModel extends RYPDModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function testDB()
    {
        $sql = "
            SELECT
            *
            FROM
            test_table
        ";

        return $this->more(Tool::getPreArr($sql));
    }
}

?>
