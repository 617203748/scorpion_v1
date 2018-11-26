<?php
	/**
 	* 功能说明：<工厂方法类>
 	* ============================================================================
 	* 版权所有：蒲公英商贸有限公司技术部
 	* ----------------------------------------------------------------------------
 	* 作者：蒲公英技术部 
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");
    
	class TPL extends Smarty
	{
		//存放私有对象
		private static $instance;
		private static $TEMPLATE_EXT;
		//公共静态方法获取实例化对象
		public static function getInstance()
		{  //建立smarty实例对象$smarty
			if(!self::$instance instanceof self)
			{
				self::$instance = new self();
			}
			return self::$instance;
		}
         
		public function __construct()
		{
			parent::__construct();
			$this->setConfigs();
		}

		//重写配置
		private function setConfigs()
		{
			

			//临时改变定界符
			$this->left_delimiter="{<";
			$this->right_delimiter=">}";
			//设置模板目录 ROOT_PATH.TPL_TPL_DIR
			//$this->template_dir =TEMPLATE_DIR; //ROOT_PATH.TPL_TPL_DIR;//"./templates"; 
			$this->template_dir=TEMPLATE_DIR;
			//设置编译目录
			$this->compile_dir=COMPILE_DIR; // ROOT_PATH.TPL_COMPILE_DIR; //"./compile";
			//设置缓存目录
			$this->cache_dir=CACHE_DIR; // ROOT_PATH.TPL_CACHE_DIR; //"./smarty_cache";//缓存文件夹
		
			//强迫编译 (生产环境不适合用 false)
			$this->force_compile = FORCE_COMPILE; // FORCE_COMPILE; 
			//是否使用缓存 true/false
			$this->caching= CACHING; // TPL_IS_CACHE;
			//调试 生产环境不适合用 true/false
			$this->debugging= DEBUGGING; // DEBUGGING;  
			 //缓存存活时间（秒）
			$this->cache_lifetime = CACHE_LIFETIME; //CACHE_LIFETIME; 
			
			
			//模板后缀
			$this->setTempExt();
		}
		//模板后缀
		private function setTempExt()
		{

			self::$TEMPLATE_EXT=array
						(
							'.html',
							'.tpl',
							'.shml',
							'.htm',
							'.tp'
						);
		}
			//查看是否有缓存 cache
		
		public function isCached($templateName = null, $cacheKey = null, $compile_id = null, $parent = null)
		{
			$templateName=$this->isEx($templateName);
			$cacheKey=md5($_SERVER['REQUEST_URI']);
			if($this->is_cached($templateName,$cacheKey))
			{
				echo 'TPL.class.php  isCached 读取缓存 html 文件 ........';
				$this->display($templateName);
				exit;
			}
			else
			{
				echo '缓存关闭!!!!!! 直接读取数据库 ';
			}
		}
		
					
		private function isEx($value)
		{
			//不写后缀
			if(!strpos($value,'.'))
			{
				for($i=0;$i<count(self::$TEMPLATE_EXT);$i++)
				{
					//echo $this->TEMPLATE_EXT[$i];
					if(file_exists(TEMPLATE_DIR.$value.self::$TEMPLATE_EXT[$i]))
					{

						 $value=$value.self::$TEMPLATE_EXT[$i];
						 return $value;
						// break;

					}
				}

				//$value.=$this->TEMPLATE_EXT['tpl'];
			}

			//写后缀
			if(strpos($value,'.'))
			{	
				$myext=substr($value,strpos($value,'.'));
				if(in_array($myext,self::$TEMPLATE_EXT))
				{		
					
					//$value.=$this->TEMPLATE_EXT[substr($myext,strpos($myext,'.')+1)];
					return $value;
				}				
			}

			return TEMPLATE_DIR.$value.'  -----模板不存在！';
			
		}
		//清楚全部缓存
		public function clearAllCache()
		{
			$this->tpl->clear_all_cache();//清除所有缓存
			
		}
		//清楚指定文件的缓存
		public function clearCache($templateName)
		{
			$this->tpl->clear_cache($templateName);//清除index.tpl的缓存
		}
		//从写父类的 display 加入后缀名判断
		public function display($templateName = null, $cacheKey = null, $compile_id = null, $parent = null)
		{
			$cacheKey=md5($_SERVER['REQUEST_URI']);
			parent::display($this->isEx($templateName),$cacheKey);
		}
		//私有克隆
		private function __clone(){}
	}
?>