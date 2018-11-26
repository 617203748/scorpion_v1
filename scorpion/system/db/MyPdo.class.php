<?php
	/**
 	* 功能说明：<数据库连接类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：郭永恩，李鹏
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class MyPdo {	
        protected $statement;
        protected $result;
        protected $mode;
        private static $get_mode;
        private static $get_fetch_style;
        private  $chars="SET NAMES 'utf8mb4';";

        // 数据库连接ID 支持多个连接
        protected $pdoArr = array();
		// 外部调用pdo用于防注入
        public $pdo=null;
      
		// PDO连接参数
        protected $options = array (
			//长链接打开会出现  Warning: PDO::__construct() [pdo.--construct]: MySQL server has gone away
			PDO::ATTR_PERSISTENT=>false
		);

        private $config;
        private $key;
        private $arrValue;

        // 构造函数
		public function __construct($config) 
		{  
			$this->config=$config;
			$this->connect($this->config);
		} 
		//拦截器(__set)
	public function __set($key,$value) 
	{
	    $this->$key =$value;
	}

	//拦截器(__get)
	public function __get($key) 
	{
	    return $this->$key;
	}
		// pdo持久连接
		// $dbh = new PDO('mysql:host=localhost;dbname=test', $user, $pass, array(PDO::ATTR_PERSISTENT => true));
		// 获取PDO实例
		// $pconnect 是否是否用长连接
    	public function connect()
    	{ 
			if (!class_exists('PDO')) throw_exception("不支持:PDO");

			$key= md5($this->config['db_dsn']);
			
			//如果没有该实例 就进行实例化 用数据库名字 来识别连接
			if(!isset($this->pdoArr[$key]))
			{     
				try
				{
					if(version_compare(PHP_VERSION,'5.3.6','<='))
					{ 
						//禁用模拟预处理语句，禁用prepared statements的仿真效果 启用真实效果 用预处理可以杜绝SQL注入，如果是false，读写分离会有问题，现在只能为true
						$this->options[PDO::ATTR_EMULATE_PREPARES] = true;
					}
					
					// 连接数据库                            
					$this->pdoArr[$key] =new PDO($this->config['db_dsn'], $this->config['db_user'], $this->config['db_pass'],$this->options);
					
					// 设置数据库连接字符串
					$this->pdoArr[$key]->exec($this->config['db_charset']);

					// 自定义异常
					$this->pdoArr[$key]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
				}
				catch(PDOException $e)
				{
					// throw $e->getMessage();
					exit(self::setExceptionError($e->getMessage(),$e->getLine(),$e->getFile()));
				}
			}
			
			$this->pdo=$this->pdoArr[$key];
    	} 
		
 
		/**
		 * [setAttribute 设置属性]
		 * @param [type] $sql         [description]
		 * @param [type] $fetchStyle [description]
		 * @param [type] $mode        [description]
		 */
		private function setAttribute($sql,$fetchStyle,$mode)
		{   
			$this->mode=self::getMode($mode);
			$this->pdo->setAttribute(PDO::ATTR_CASE,$this->mode);
			//查询
			$this->statement=$this->pdo->query($sql);
			$this->statement->setFetchMode(self::getStyle($fetchStyle));
			return $this->statement;
		}


		/**
		* [fetchStyle 返回结果集的类型]
		* @param  [type] $style [结果集类型]
		* @return [type]         [根据选择的类型返回想对应的结果集]
		*/
		private static function getStyle($style='object')
		{

			switch($style)
			{
				case 'assoc':
					self::$get_fetch_style=PDO::FETCH_ASSOC;//返回一个索引为结果集列名的数组
					break;
				case 'num':
					self::$get_fetch_style=PDO::FETCH_NUM;//返回一个索引为以0开始的结果集列号的数组
					break;
				case 'object':
					self::$get_fetch_style=PDO::FETCH_OBJ;//返回一个属性名对应结果集列名的匿名对象
					break;
				case 'both':
					self::$get_fetch_style=PDO::FETCH_BOTH;//返回一个索引为结果集列名和以0开始的列号的数组
					break;
				default:
					self::$get_fetch_style=PDO::FETCH_ASSOC;//返回一个索引为结果集列名的数组
					break;
				
			}

			return self::$get_fetch_style;
		}

		/**
		* [getMode 返回的列名是什么类型]
		* @param  [type] $get_style [传入的值]
		* @return [type]            [列名类型]
		*/
		private static function getMode($get_style)
		{
			switch($get_style)
			{
				case null:
					self::$get_mode=PDO::CASE_NATURAL;//保留数据库驱动返回的列名
					break;
				case true:
					self::$get_mode=PDO::CASE_UPPER;//强制列名大写
					break;
				case false:
					self::$get_mode=PDO::CASE_LOWER; //强制列名小写
					break;
				default:
					 return self::$get_mode=PDO::CASE_NATURAL;
					 break;
			}

			return self::$get_mode;
		}
	
		/**
		* [setExceptionError 设置异常]
		* @param [type] $getMessage [description]
		* @param [type] $getLine    [description]
		* @param [type] $getFile    [description]
		*/
		private static function setExceptionError($getMessage,$getLine,$getFile)
		{
			echo '错误信息 '.$getMessage.'<br/> 这个错误在 '.$getLine.' 行 <br/> 这个文件路径在'.$getFile;
			exit();
		}

		////////////////////////// 数据库预处理start //////////////////////////
		// prepare 预处理语句
		public function prepare($sql)
		{   
			if (!isset($sql) || empty($sql))
			{
				return;
			}
			$this->free(); 
			$this->statement=$this->pdo->prepare($sql);
			
		}


		//绑定的数据类型
		public function getDataType($v='varchar')
		{
			
			switch($v)
			{
				case 'bool':
					return  PDO::PARAM_BOOL;
					break;
				case 'null':
					return  PDO::PARAM_NULL;
					break;
				 case 'int':
					return  PDO::PARAM_INT;
					break;
				 case 'varchar':
					return   PDO::PARAM_STR;
					break;
				 case 'lob':
					 return PDO::PARAM_LOB;
					break;
				default:
					 return PDO::PARAM_STR;
					 break;
			}
		}
    
		/**
		* [preBindParam 以引用的方式绑定变量到占位符(可以只执行一次prepare，执行多次bindParam达到重复使用的效果) 和执行方法  execute 公用]
		* @param  [type]  $parameter [参数名]
		* @param  [type]  $variable  [值]
		* @param  [type]  $data_type [值类型如 PDO::PARAM_INT PDO::PARAM_STR]
		* @param  integer $length    [值的长度]
		* @return [type]             [description]
		*/
		public function bindParam($arrValue)
		{
			if(isset($arrValue))
			{ 
				if(is_array($arrValue))
				{ 
					foreach($arrValue as $k=>$v)
					{
						if(is_array($v))
						{
							//带参数类型
							$this->statement->bindValue($k,$v[0],$this->getDataType($v[1]));
						}
						else
						{
							$this->statement->bindValue($k,$v,$this->getDataType('varchar'));
						}
						
					}
				}
				else
				{
					echo 'sql参数必须是数组 如: array(key=>value)';
				}
			}
		}
		//
		public function setReturnData($fetchStyle='object')
		{
			if(is_object($this->statement))
			{	$fetchSyle=$fetchStyle;
				
				$this->statement->setFetchMode(self::getStyle($fetchSyle));
				
			}
			else
			{
				return false;
			}
			return $this->statement;
		}
		public function sqlParams($par,$value,$dataType='varchar')
		{

			$this->statement->bindValue($par,$value,$this->getDataType($dataType));
			//echo $value;
			
		}

		/**
		 * [execute 执行预处理语句 配合prepare,bindParam 共同使用,也可以不用bindParam ]
		 * @param  string $param [数组参数 如:array(':id'=>1,':name'=>'name',...,':N'=N_Value)]
		 * @return [type]        [description]
		 */
		public function execute($param='')
		{ 
			if(is_array($param))
			{
				try
				{
					return  $this->statement->execute($param);
				}
				catch (Exception $e)
				{
					//return $e->getMessage();
					Log::write(date('Y-m-d H:i:s', time()).' '.$e->getMessage());
                    throw $e;

				}
			}
			else
			{
				try
				{
					
					return $this->statement->execute();
				}
				catch(Exception $e)
				{
					/* 返回的错误信息格式
					[0] => 42S22
					[1] => 1054
					[2] => Unknown column 'col' in 'field list'
					return $this->errorInfo();
					*/
					//return  $e->getMessage();
					Log::write(date('Y-m-d H:i:s', time()).' '.$e->getMessage());
                    throw $e;
				}
			}
		}

		
		////////////////////////// 数据库预处理end //////////////////////////

		////////////////////////// 事务end //////////////////////////
		// 开始事物
		public function begin()
		{ 
			return $this->pdo->beginTransaction();
		}

		// 提交事物
		public function commit()
		{
			return $this->pdo->commit();
		}

		// 回滚事物
		public function rollback()
		{
			return $this->pdo->rollback();
		}
		////////////////////////// 事务end //////////////////////////
    
		// 关闭数据库 如果不主动关闭数据库在页面执行结束的时候，pdo自动关闭]
		public  function close()
		{
			$this->pdoArr = null;
		}


		// 释放查询结果
		public function free() 
		{
			if(!is_null($this->statement))
			{
				$this->statement->closeCursor();
				$this->statement = null;
			}
		  
		}

		// 析构方法
		public function __destruct() {
			// 释放查询
			if ($this->statement){
				$this->free();
			}
			// 关闭连接
			$this->close();
		}
		
		


		////////////////////////// PDO数据库操作模型 //////////////////////////
		// 获取一条记录
		public function one($sqlPar=array(),$fetchStyle='object')
		{	
			$this->prepare($sqlPar['sql']);
			//sql参数
			$this->bindParam($sqlPar['value']);

			//执行操作
			$this->execute();

			$this->setReturnData();
			
			return $this->statement->fetch();
		}


	
		// 获取多条记录
		public function more($sqlPar=array(),$fetchStyle='object')
		{
			$this->prepare($sqlPar['sql']);
			//sql参数
			$this->bindParam($sqlPar['value']);

			//执行操作
			$this->execute();

			$this->setReturnData();
			return $this->statement->fetchAll();
		}
		
		// 执行增改删操作，返回影响的行数 
		public function aud($sqlPar)
		{

			// 预处理sql
			$this->prepare($sqlPar['sql']);
			
			// sql参数
			$this->bindParam($sqlPar['value']);
            try
            {
                // 执行
                return $this->execute();
            }
            catch (Exception $e)
            {
				Log::write(date('Y-m-d H:i:s', time()).' '.$e->getMessage());
                throw $e;
			}
			


		}

		// 获取一个字段 
		public function total($sqlPar=array())
		{
			$sqlPar['fetchStyle']='num';
			$rs=$this->one($sqlPar);
			return $rs[0];
		}

		// 获取表的最新的自动增长id号
		public function nextid($table)
		{
			if (!is_null($this->pdo))
			{
				$rs = $this->pdo->query("SHOW TABLE STATUS LIKE '$table'"); 
				$row = $rs->fetch();

				return $row['Auto_increment'];

				// return $this->pdo->lastInsertId();
			}
			else
			{
				return false;
			}
		}


		// 执行事务
		public function transaction($sqlArray)
		{
			try
			{  
				$this->begin();
			   
				foreach($sqlArray as $v)
				{
					$this->result = $this->pdo->exec($v);
               
				}

				$this->commit();

				return true;
			}
			catch(PDOException $e)
			{	
				$this->rollback();

				// exit($e->getMessage());
			}

			return false;
		}



		
	}
?>