<?php

 /**
 	* 功能说明：<点餐购物车自定义工具控制类>
 	* ============================================================================
 	* 版权所有：北京北门外科技有限公司
 	* ----------------------------------------------------------------------------
 	* 开发团队：维真技术部
	*/

	if(!defined('RYPDINC')) exit("Request Error!!!");
	class CURDCache
	{

		private $tableKey='';//'1'.'2'.'2';
		private $myMem=null;
		private $order=null;
		private $compid;
		private $shopid;
		private $tableid;
		private $serviceid;
		private $areaid;
		public function __construct($publicID)
		{
			$this->__init($publicID);
		}
	  //拦截器(__set)
		public function __set($key,$value) 
		{
		    $this->$key = $value;
		}

		//拦截器(__get)
		public function __get($key) 
		{
		    return $this->$key;
		}
		private function __init($publicID)
		{

			//Tool::check($publicID);
				/*
            $this->compid=1;
			$this->shopid=2;
			$this->tableid=3;
			$this->serviceid=4;*/
			if(count($publicID)>0)
			{
				// session中获取公司id
				$this->compid = isset($publicID['compid']) ? $publicID['compid'] : -1;//Tool::getSession('meal_compid');          

				// session中获取餐厅id
				$this->shopid = isset($publicID['shopid'])? $publicID['shopid'] : -1;//Tool::getSession('meal_shopid');
               
				// session中获取餐桌id
				$this->tableid = isset($publicID['tableid'])? $publicID['tableid'] : -1;//Tool::getSession('meal_tableid');

				// session中获取服务员id
				$this->serviceid = isset($publicID['serviceid'])? $publicID['serviceid'] : -1;//Tool::getSession('meal_serviceid');

				//$this->myMem=$this->myMem=new MemcachedCache();
				//区域id
				$this->areaid = isset($publicID['areaid'])? $publicID['areaid'] : -1;//Tool::getSession('meal_serviceid');
				
				$this->myMem=MemcachedCache::getInstance();
			}
			else
			{

				echo '请给 CURDCache 传入参数!!';
			}
		}


        // 获取基本的,公共的Key 
		private function getBaseKey()
		{
			// 公共的Key 
			$key =PUBLICKEY;//'123456';
			return $key;
		}


		// 获取公司key
		private function getCompanyKey()
		{
			$key =  $this->getBaseKey().$this->compid;
			return $key;
		}


		// 获取餐厅key
		private function getShopKey()
		{
			$key =  $this->getCompanyKey().$this->shopid;
			return $key;
		}

		// 获取服务员key
		private function getServiceKey()
		{
			$key =  $this->getShopKey().$this->serviceid;//.$this->serviceid;
			return $key;
		}


		// 获取餐桌key
		private function getTableKey()
		{
			$key =  $this->getShopKey().$this->tableid;//.$this->serviceid;
			return $key;
		}
		//获取 cud 时间的key 
		private function getCudTimeKey()
		{
			$key=$this->getTableKey().'CUDTIME';
			return $key;
		}

		//获取不同类型的公共key 该key用于 cud 和 r 通信
		private function getPublicKey($keyType='T')
		{
			$publicKey=null;
			switch (strtoupper($keyType))
			{
				case 'B':
						 $publicKey= $this->getBaseKey();
						 break;
				case 'C':
						 $publicKey= $this->getCompanyKey();
						 break;
				case 'S':
						 $publicKey= $this->getShopKey();
						 break;
				case 'T':
						 $publicKey= $this->getTableKey();
						 break;
				case 'C':
						 $publicKey= $this->getCudTimeKey();
						 break;
				case 'W':
				         $publicKey=$this->getServiceKey();  // 服务员
				         break;
			}

			return $this->keyName($publicKey);
		}


      

		//设置表 的cud时间
		public function setMemCUDTime($tableArr,$keyType='T')
		{
			$cudTime=microtime();
			$publicKey=$this->getPublicKey($keyType);
			$cacheDataArr=$this->getMem($publicKey);
		
			if($cacheDataArr)
			{
				foreach($tableArr as $k=>$tName)
				{	
					//$tName=$this->keyName($tName);
					if(!array_key_exists($tName,$cacheDataArr))
					{
						$cacheDataArr[$tName]['cudtime']=$cudTime;
						
					}
				}
			}
			else
			{
				foreach($tableArr as $key=>$tName)
				{
					//$tName=$this->keyName($tName);
					$cacheDataArr[$tName]['cudtime']=$cudTime; //$this->keyName($tName);
				}
			}
			$this->setMem($publicKey,$cacheDataArr);
			return $cacheDataArr;
			
		}


		


		//设置memcached 的具体值
		public function setMemData($tableArr,$sql,$value,$keyType='T')
		{	
			if(count($value)>0)
			{
				
				$tableArr=$this->convertArray($tableArr);
				$cacheDataArr=$this->setMemCUDTime($tableArr,$keyType);
				//数据与数据所需的表的 cud时间
				$dataAndcudTime['cachedata']=$value;
				if($cacheDataArr)
				{
					
					foreach($tableArr as $tName)
					{
						//$tName=$this->keyName($tName);
						if(array_key_exists($tName,$cacheDataArr))
						{
							//$value[$tName]['cudtime']=$cacheDataArr[$tName]['cudtime'];
							//设置缓存数据 cud 时间
							$dataAndcudTime[$tName]['cudtime']=$cacheDataArr[$tName]['cudtime'];
						}
						
					}
					//设置缓存数据 cud 时间
					//$this->setMem($this->getPublicKey('C').$sql,$dataCudTime);
					//设置缓存数据
					$this->setMem($this->getPublicKey($keyType).$sql,$dataAndcudTime);
				}
				
			}

			return false;
			
		}
		// 判断是直接读数据库还是缓存
		public function isMemValue($tableArr,$keyType='T')
		{	
			
			$publicKey=$this->getPublicKey($keyType);
			$cacheDataArr=$this->getMem($publicKey);
			if($cacheDataArr)
			{	
				foreach($tableArr as $tName)
				{
					//$tName=$this->keyName($tName);
					if(!array_key_exists($tName,$cacheDataArr))
					{	
						//读取数据库
						return false;
					}
					
				}
			}
			else
			{   //读取数据库	
				return false;
			}
			//echo '公共表时间';
			//var_dump($cacheDataArr);
			return $cacheDataArr;
			
		}
        // 读取memcached缓存的具体值
		public function getMemData($tableArr,$sql,$keyType='T')
		{	
			$tableArr=$this->convertArray($tableArr);
			$cacheDataArr=$this->isMemValue($tableArr,$keyType);
			//echo 1;
			//Tool::check($cacheDataArr);
			if($cacheDataArr)
			{
				//echo 2;
				$sqkKey=$this->getPublicKey($keyType).$sql;
				//获取缓存数据的 cud 时间
				$dataAndcudTime=$this->getMem($sqkKey);
				//Tool::check($dataAndcudTime);
				foreach($tableArr as $tName)
				{
					//$tName=$this->keyName($tName);
					if(array_key_exists($tName,$cacheDataArr))
					{	//根据时间 cud 时间判断是否需要读库 无论多少张表 只要有一张时间不相等 那么就去读库
						if($dataAndcudTime[$tName]['cudtime']!=$cacheDataArr[$tName]['cudtime'])
						{
							
							return false;
						}
						
					}
				
				}
				//echo '数据和 私有表时间';
				//var_dump($dataAndcudTime);
				//读取缓存数据
			   return $dataAndcudTime['cachedata'];//$this->getMem($sqkKey);
			}
			//当 删除了 一张表时 直接去读库
			return false;
		}
		
	
		
		// 删除table的缓存
		public function delMemValue($tableArr,$keyType='T')
		{	
			 $tableArr=$this->convertArray($tableArr);

			 $publicKey=$this->getPublicKey($keyType);

			 $cacheDataArr=$this->getMem($publicKey);

			
			if($cacheDataArr)
			{
				 foreach($tableArr as $tName)
				 {
					 //$tName=$this->keyName($tName);
					 if(array_key_exists($tName,$cacheDataArr))
					 {
						
						unset($cacheDataArr[$tName]);
						
						//$cacheDataArr[$tName]=
					 }
				 }
			 // echo $publicKey;
			 // echo '<br>';
			 // Tool::check($cacheDataArr);
			 // echo '<br>';
			 // Tool::check($tableArr);exit;

				if(count($cacheDataArr)>0)
				{

				  return $this->setMem($publicKey,$cacheDataArr);
				}
				if(count($cacheDataArr)==0)
				{
					return $this->deleteMem($publicKey);
				}
			}	


		}


		//参数格式  array('table1','table2','.....','tableN') 或 'table1','table2','....','tableN'
		private function convertArray($value)
		{
			if(is_string($value))
			{
				$value=explode(',',$value);
				return $value;
			}
			if(is_array($value))
			{
				return $value;
			}
		}
	
	    // keyName-MD5
		private function keyName($value)
		{
			return md5($value);
		}

		
        // 给key设置Mem
		private function setMem($key,$value)
		{
			return $this->myMem->set($key,$value);
		}

        
		// 获取key的Mem
		private function getMem($key)
		{
			return $this->myMem->get($key);
		}

		//删除key的所有数据
		public  function deleteMem($key)
		{
			$this->myMem->delete($key);
		}

		//清空缓存 这个方法 慎用 执行后会把 memecached 中的所有 值全部清空
		public function deleteAll()
		{
			$this->myMem->flush();
		}
	}
?>