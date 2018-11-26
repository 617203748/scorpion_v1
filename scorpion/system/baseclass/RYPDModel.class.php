<?php
/**
 * 功能说明：<模型基类>
 * ============================================================================
 * 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 * ----------------------------------------------------------------------------
 * 作者：郭永恩，李鹏
 */
if (!defined('RYPDINC')) exit("Request Error!!!");

class RYPDModel
{
    // 数据库实例
    protected $db;

    protected $isTran=false;
    // 验证模型类
    protected $check = null;

    protected $command;

    public function __construct()
    {
        $this->DBInit();
    }


    // 切换当前的数据库驱动
    public function DBInit()
    {
        $this->db = DB::getInstance();

        $this->command = new MySqlCommand($this->db);


    }


    // 删除文件
    public function deleteFile($file)
    {
        $flag = false;
        $file = iconv('utf-8', 'gb2312', $file);

        if (file_exists($file)) {
            $flag = @chmod($file, 0777);
            $flag = @unlink($file);
        }
        return $flag;
    }


    public function getPreArr($sql = null, $value = null)
    {
        if (null != $sql) {

            echo 'sql语句没有写';
            exit;
        }

        return array('sql' => $sql, 'value' => $value);
    }


    // 字符串加密
    public function getEncryptString($str)
    {
        return Tool::getEncryptString($str);
    }

    // 获取一条记录
    /*
        用法：
        $sql = "SELECT * FROM student WHERE id=:id ;";

        $sqlConfig = Tool::getPreArr(
                            $sql,
                            array(':id'=>'7')
                  );
        $this->one($sqlConfig);
    */

    public function bindParam($k = null, $v = null, $dataType = 'varchar')
    {

        $this->db->bindParam($k, $v, $dataType);
    }


    public function one($sqlConfig)
    {

        return $this->db->one($sqlConfig);
    }

    // 获取多条记录
    /*
        用法：
        $sql = "SELECT * FROM student where ;";
          $sqlConfig = Tool::getPreArr($sql);
        $this->more($sqlConfig);
    */
    public function more($sqlConfig)
    {
        return $this->db->more($sqlConfig);
    }

    // 获取一个字段
    /*
        用法：
        $sql = "SELECT COUNT(*) FROM student ;";

        $sqlParams = Tool::getPreArr(
                            $sql
                  );
        $this->one($sqlConfig);
    */
    public function total($sqlConfig)
    {
        return $this->db->total($sqlConfig);
    }

    // 执行增改删操作，返回影响的行数
    /*
        用法：
        $sql = "UPDATE student SET sname=:sname WHERE id=:id ;";

        $sqlConfig = Tool::getPreArr(
                            $sql,
                            array(':sname'=>'赵云',':id'=>'7')
                  );
        $this->aud($sqlConfig);
    */
    public function aud($sqlConfig)
    {   
        try
        {
            return $this->db->aud($sqlConfig);
        }
        catch (Exception $e)
        {
            if ($this->isTran) {
                throw $e;
            }
            return false;
        }
    }


    // 获取指定表最新的自增长id号
    /*
        用法：
        $table = 'cook_user';
        $this->nextid($table);
    */
    public function nextid($table)
    {
        return $this->db->nextid($table);
    }


    // 获取唯一不重复的id为组合的最终自定义id(f130eb1a41c99e68052236a9c002cd5d)
    /*
        用法：
        $table = 'cook_user';
        $this->getCustomId($table);
    */
    public function getCustomId($table)
    {
        // 获取指定表最新的自增长id号
        //$id = $this->nextid($table);

        // 生成年月日字符串(20160101)
        //$dateString = date("Ymd");
        //$id=md5($table.microtime().mt_rand(100000,999999));
        $id = md5($table . microtime() . mt_rand(strtotime(microtime()), 99999999999999999) . Tool::GetfourStr(32) . Tool::uuid($table));
        // 返回最终的用户id(201601019)
        return $id;
    }


    // 执行事务
    /*
        用法：
        $sqlarr = array(
            "INSERT INTO `student` (`id`, `name`, `sex`, `age`) VALUE ('80001', 'test1', '女', '27');",
            "INSERT INTO `student` (`id`, `name`, `sex`, `age`) VALUE ('80002', 'test2', '女', '27');",
            "INSERT INTO `student` (`id`, `name`, `sex`, `age`) VALUE ('80003', 'test3', '女', '27');",
            "INSERT INTO `student` (`id`, `name`, `sex`, `age`) VALUE ('80004', 'test4', '女', '27');",
            "INSERT INTO `student` (`id`, `name`, `sex`, `age`) VALUE ('80005', 'test5', '女', '27');"

        );
        var_dump($this->model->transaction($sqlarr));
    */
    public function transaction($sqlArray)
    {
        return $this->db->transaction($sqlArray);
    }


    //富文本编辑器 图片路径替换
    public function replaceImageRoot($detail)
    {
        return preg_replace('/\/upload\//i', UPLOAD_ROOT, $detail);
    }


    public function begin()
    {
        $this->isTran =true;
        return $this->db->begin();
    }

    public function commit()
    {

        return $this->db->commit();
    }

    public function rollback()
    {

        return $this->db->rollback();
    }
}

?>