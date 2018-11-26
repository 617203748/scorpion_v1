<?php
	/**
 	* 功能说明：<网站控制器基类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：郭永恩，李鹏
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class WebAppAction{
		// 模板引擎对象
		public $tpl = null;
		// 数据模型类
		protected $model = null;

		protected $config=null;


		protected function __construct(&$model=null){
			$this->tpl = TPL::getInstance();
			$this->model = $model;

			//$this->getWechatUserInfo();

	
			$this->config=array();

		
            
		}

		// 根据简单工厂获得的控制器类执行该类相应的方法
		public function run()
		{ 
			$method = isset($_GET['m']) ? $_GET['m'] : 'index';
		
			if(method_exists($this, $method)){
				$this->$method();
			}
			else{

				// 方法不存在跳转
				Tool::alertLocation(null,'/main/error/notfound.html');
			}
		}

		// 获取地址
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

		////////////////////// 公用方法 //////////////////////

		// 分页
		public function page($total,$pagesize = PAGE_SIZE) {

			$page = new Page($total, $pagesize);
			

			$this->model->limit = $page->limit;
			$this->tpl->assign('page',$page->show());
			$this->tpl->assign('num',($page->page-1)*$pagesize);

			$this->tpl->assign('total_page',ceil($total / PAGE_SIZE));
			$this->tpl->assign('page_url',$this->setUrl());
		
		}

		// 设置fck文办编辑器路径
		public function setFCKEdit()
		{
			$this->tpl->assign('FCK_BASE_PATH',FCK_BASE_PATH);
			$this->tpl->assign('FCK_JS',FCK_JS);
			$this->tpl->assign('FCK_WIDTH',FCK_WIDTH);
			$this->tpl->assign('FCK_HEIGHT',FCK_HEIGHT);
		}



	}
?>