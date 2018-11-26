<?PHP
/**
    * 功能说明：<文件自动加载类>
    * ============================================================================
    * 版权所有：蒲公英商贸有限公司技术部
    * ----------------------------------------------------------------------------
    * 作者：蒲公英技术部
    * ----------------------------------------------------------------------------
    * 日期：2015.06.15
    */
    class Loader
    {
		//类路径
		private static $loadPath;
		//customLoadPath 该方法的开关
		private static $isCustomLoad=true;

		//是否开启session 默认为开启状态
		private static $isSession=true;
		//设置路径
		public static function customLoadPath($path=array())
		{	
			self::initLoadPath();
			//如果输入的是字符串 就包装成数组
			if(!is_array($path))
			{
				$path=array($path);
				
			}
			if(count($path)>0)
			{	//合并数组
				self::$loadPath=array_merge(self::$loadPath,$path);
				//是否用了此方法的标志位
				self::$isCustomLoad=false;
			}
			
		}
		//初始化路径
		private static function initLoadPath()
		{
			 self::$loadPath=require SYS_CONFIG_PATH.'autoload_path.init.php';
		}
		//加载类路径
        public static function loading()
        {		
			  if(self::$isCustomLoad)
			  {
				self::initLoadPath();
			  }
			// print_r(self::$loadPath);exit;
             //set_include_path(get_include_path().PATH_SEPARATOR.implode(PATH_SEPARATOR, self::$loadPath));
             set_include_path(implode(PATH_SEPARATOR, self::$loadPath));
             spl_autoload_register(array('Loader','_autoload'));  

			
        }
		//自动加载注册的类
        public static function _autoload($classname)
        {	
            if (strpos($classname,"Smarty_")===0 || strpos($classname,"SmartyException")===0|| strpos($classname,"SmartyCompilerException")===0) {
        			
        			require_once strtolower($classname).".php";
        		}else{
        			
        			$filename=$classname.EXT;

            		require_once $filename;
        		}

			
        }

        private static function fileType($classname)
        {
			
        	switch($name)
        	{

        	}
        }
		//开启$_SESSION //开启session 在加载完所有类后再开启 session 会使整个网站 共享session
		public static function startSession()
		{
			//微信验证不需要开session
			if(self::$isSession)
			{
				//开启session 在加载完所有类后再开启 session 会使整个网站 共享session
				if(!isset($_SESSION))
				{
					session_start();
				}
			}
		}
		//设置session是否开启
		public static function closeSession()
		{
			self::$isSession=false;
		}
    }

   
?>