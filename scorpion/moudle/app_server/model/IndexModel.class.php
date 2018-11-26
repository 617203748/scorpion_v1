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

    public function testDB_query_one($params)
    {
        $sql = "
            SELECT
            *
            FROM
            test_table
            WHERE 
            id = :id
        ";

        $key = Tool::getPreArr($sql, array(
            ':id' => $params['id']
        ));

        return $this->one($key);
    }

    public function testDB_query_more()
    {
        $sql = "
            SELECT
            *
            FROM
            test_table
        ";

        return $this->more(Tool::getPreArr($sql));
    }

    public function testDB_insert($params)
    {
        //打印到 scorpion/logs/current.log中
        Log::write(json_encode($params, 256));

        $sql = "
            INSERT INTO
            test_table
            (`id`,`name`)
            VALUES 
            (:id,:name)
        ";

        $key = Tool::getPreArr($sql, array(
            ':id' => $params['id'],
            ':name' => $params['name']
        ));

        return $this->aud($key);
    }
}

?>
