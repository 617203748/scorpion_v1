<?php
	/**
 	* 功能说明：<微信控制基类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：太原锐意鹏达科技有限公司
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");
	class WeChatAction{
		//数据模型类
		protected $model = null;
	
		protected function __construct(&$model=null){

			if(Validate::isValidate()){

				$this->model = $model;

			}
		}

		//根据简单工厂获得的控制器类执行该类相应的方法
		public function run(){
			$method = isset($_GET['m']) ? $_GET['m'] : 'index';
			if(method_exists($this,$method)){
				//eval('$this->' . $method.'();');
				$this->$method();
			}
			else{
				//exit($method.'方法不存在');
				//Tool::alertLocation(null,'error/notfound.html');
				Tool::alertErrorPage('notfound');
			}
		}

		
		//获取地址
		private function setUrl() {
			$url = $_SERVER["REQUEST_URI"];
			$par = parse_url($url);
			if (isset($par['query'])) {
				parse_str($par['query'],$query);
				unset($query['page']);
				$url = $par['path'].'?'.http_build_query($query);
			}
			return $url;
		}

	}
?>