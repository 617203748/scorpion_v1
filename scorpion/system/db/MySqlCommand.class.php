<?php

class MySqlCommand
{

	private $db;
	private $sqlText=null;

	private $pars;

	public function __construct($db=null)
	{   
		$this->db=$db;
		
	}

	
	

	public function setSqlText($sqlText=null)
	{
		

		if(null==$sqlText)
		{
			echo 'sql语句不能为空';
			return;
		}
		$this->sqlText=$sqlText;
		$this->db->prepare($this->sqlText);

		

	}

	
	public function add($par=null,$value=null,$dataType='varchar')
	{
		
		$par=trim($par);
		$value=trim($value);
		

		if(is_null(trim($par)) || ''===$par)
		{
			echo 'sql参数不能为空';
			exit;
		}
		if(is_null(trim($par)) || ''===$value)
		{
			echo 'sql参数值不能为空';
			exit;
		}
		
		if($dataType=='int')
		{
			$value=(int)$value;
		}
		$this->db->sqlParams(trim($par),$value,$dataType);
	}

	public function execute()
	{ 
		if($this->db->execute())
		{
			return $this;
		}
		return false;
	}


	public function one($fetchStyle='object')
	{	
			
			return $this->db->setReturnData($fetchStyle)->fetch();
	}

	// 获取多条记录
	public function more($fetchStyle='object')
	{
		return $this->db->setReturnData($fetchStyle)->fetchAll();
	}

	// 析构方法
	public function __destruct() {
		
		unset($this);
	}


}