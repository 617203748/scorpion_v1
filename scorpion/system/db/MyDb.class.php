<?php
	/**
 	* 功能说明：<MySqli数据库操作类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：郭永恩，李鹏
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class MyDb {
	
		private $db;

		public function __construct()
		{  
			//获取数据库对象
			$this->db = new mysqli(DB_HOST,DB_USER, DB_PASS, DB_NAME);
			
			if(mysqli_connect_errno())
			{
				echo '数据库连接错误!错误代码：' . mysqli_connect_error();
				exit;
			}
			
			$this->db->set_charset('utf8');
			
		}

		// 释放数据库
		public function unDB(&$result)
		{
			if(is_object($result))
			{
				$result->free();
				$result=null;
			}
		}

		// 析构方法
		public function __destruct() 
		{
		   if(is_object($this->db))
			{
				$this->db->close();
				$this->db = null;
			}
		}

		// 格式化sql语句
		private function formatSql($sql)
		{
			foreach($sql['value'] as $k=>$v)
			{
				$sql['sql'] = str_replace($k, "'".$v."'", $sql['sql']);
			}
			return $sql['sql'];
		}

		// 执行多条SQL语句
		public function multi($sql) 
		{	
			$this->db->multi_query($sql);
			$this->unDB($result = null);
			return true;
		}

		////////////////////////// 增册查改start //////////////////////////
		// 获取表的最新的id号
		public function nextid($table) {
			$sql = "SHOW TABLE STATUS LIKE '$table'";
			$object = $this->one($sql);
			return $object->Auto_increment;
		}
		
		// 获取一个字段
		public function total($sql) {
			$result =$this->db->query($sql);
			$total = $result->fetch_row();
			$this->unDB($result);
			return $total[0];
		}

		// 获取单条记录
		public function one($sql) {	
			$result = $this->db->query($this->formatSql($sql));
			$objects = $result->fetch_object();
			$this->unDB($result);
			return Tool::htmlString($objects);
		}
		
		// 查找多条记录(对象形式)
		public function more($sql) {
			$result = $this->db->query($this->formatSql($sql));
			$html = array();
			while (!!$objects = $result->fetch_object()) {
				$html[] = $objects;
			}
			$this->unDB($result);
			return Tool::htmlString($html);
		}

		// 查找多条记录(数组形式)
		public function allArray($sql) {
			$result = $this->db->query($this->formatSql($sql));
			$html = array();
			while (!!$objects = $result->fetch_array()) {
				$html[] = $objects[0];
			}
			$this->unDB($result);
			return $html;
		}
		
		// 执行增改删操作，返回影响的行数
		public function aud($sql) {
			$this->db->query($this->formatSql($sql));
			$affected_rows = $this->affected_rows;
			$this->unDB($result = null);
			return $affected_rows;
		}

		// 执行事务 
		public function transaction ($sqlArr=array())
		{						
			if(empty($sqlArr))
			{
				return false;
			}

			// 开始事务
			$this->db->autocommit(false); 
			
			// 语句执行不成功的返回值
			$error = 0; 
			
			// 是否有执行不成功的语句,默认都成功
			$flag = true;
			
			foreach($sqlArr as $sql)
			{	
				$this->db->query($sql);

				// 有语句错误
				if($this->db->affected_rows < $error)
				{
					// 回滚事务
					$this->db->rollback(); 
					
					$flag = false;

					return $flag;
				}
			}

			// 提交事务
			$this->db->commit(); 

			// 关闭事务
			$this->db->autocommit(true);

			// 关闭数据库
			$this->unDB($result = null);
			
			return $flag;
		}
	}
?>