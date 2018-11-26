<?php
/* 
* @Author: 李鹏
* @Date:   2015-05-03 10:06:43
* @Last Modified by:   anchen
* @Last Modified time: 2015-05-03 14:23:20
*/

class CacheStatus
{
            //是否读取缓存
            private $isCache;
    
            private $DataBaseArr;
   
            private $dbname;
            private $tablename;
            private static $ObjKey;

            private  static $read='read';
            private static $write='write';

            /*
             //拦截器(__set)
            public function __set($key, $value) 
            {
                $this->$key = Tool::mysqlString($value);
            }
            //拦截器(__get)
            public function __get($key)
             {
                return $this->$key;
            }*/
       
           public static function getRead()
           {
               return self::$read;
           }

           public static function getWrite()
           {
               return self::$write;
           }

            public static function getObjKey()
            {
                self::$ObjKey='CacheStatus.class.php_这个类是李鹏的识别测试类';
                return  self::$ObjKey;
            }
            
            public function setDataBase($dbname,$tablename)
            {
                if(empty($dbname))
                {
                    echo $dbname.'为空';
                    return;
                }
                if(empty($tablename))
                {
                    echo $tablename.'为空';
                    return;
                }
                $this->dbname=$dbname;
                $this->tablename=$tablename;

                if(!is_array($this->DataBaseArr))
                {
                    $this->DataBaseArr=array();
                }
               
                if(!isset($this->DataBaseArr[$this->dbname]))
                {

                     $this->DataBaseArr[$this->dbname]=array();
                }
            }

            //循环设置表的状态
            public function setCacheStatus($dtarr,$status='read')
            {
             
               foreach($dtarr as $dbtable)
               {
                  $arr=explode('.',$dbtable);
                  $dbname=$arr[0];
                  $table=$arr[1];
                  
                  $this->setDataBase($dbname,$table);
                 
                  $this->DataBaseArr[$dbname][$table]=$status;
               }
            }

            //循环获取表的状态
            public function getCacheStatus($dtarr)
            {
                $arr=array();
                foreach($dtarr as $dbtable)
                {
                    $arr=explode('.',$dbtable);
                    $dbname=$arr[0];
                    $table=$arr[1];
                  
                    if(!isset($this->DataBaseArr[$dbname][$table]))
                    {

                        $status="write";
                    }
                    else
                    {
                        $status=$this->DataBaseArr[$dbname][$table];
                    }
                    
                    if($status=='write')
                    {
                        return $status;
                        break;
                    }
                    $statusArr[]=$status;
                }
                return $statusArr;
            }

            /**
             * [setIsCache 设置Cache状态]
             * @param [type] $v [description]
             */
            public function setIsCache($v)
            {
                 $this->isCache=$v;
            }
            /**
             * [isCache 返回Cache状态]
             * @return boolean [description]
             */
            public function isCache()
            {
                    return $this->$isCache;
            }
          
}
?>
