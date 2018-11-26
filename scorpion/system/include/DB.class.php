<?php
/* 
* @Author: 李鹏
* @Date:   2015-04-22 09:41:41
* @Last Modified by:   anchen
* @Last Modified time: 2015-04-23 10:22:50
*/

/**
 * 数据库连接
 */
class DB
{
    //当亲数据库连接实例
    private static $insCurr=null;

   
    public static function getPDODB()//($config=array())
    {               
            $options=self::parseDsn();
            self::$insCurr=new MyPdo($options);
            return self::$insCurr;
    }
   //公共静态方法获取实例化对象
    public static function getInstance()
    {  //建立smarty实例对象$smarty
        if(!self::$insCurr instanceof MyPdo)
        {
            $options=self::parseDsn();
            self::$insCurr = new MyPdo($options);
        }
        return self::$insCurr;
    }

     /**
     * DSN解析
     * 格式： 'mysql:host=192.168.0.15;port=4040;dbname=test','root','root'
     * @static
     * @access private
     * @param string $dsnArr
     * @return array
     */
    private static function parseDsn() 
    {
        //配置数据库的连接参数 //可放多种数据库 mysql|mssql|oracle
        $dsn = array(
            'db_dsn'   => DB_TYPE.':host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_NAME,
            'db_type'  => DB_TYPE,  
            'db_host'  => DB_HOST,
            'db_port'  => DB_PORT, 
            'db_user'  => DB_USER,
            'db_pass'  => DB_PASS, 
            'db_name'  => DB_NAME, 
            'db_charset'  => "SET NAMES 'utf8mb4';",
        );
        
        return $dsn;
     }
    

	//获取数据库
	public static function getMySQLIDB()
	{		
		return new MyDb();
	}
    // 调用驱动类的方法
    static public function __callStatic($method, $params){
        return call_user_func_array(array(self::$_instance, $method), $params);
    }
    
}
?>
