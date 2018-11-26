<?php
	/**
 	* 功能说明：<工厂方法类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：太原锐意鹏达科技有限公司
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class Page {
		private $total;											//总记录
		private $pagesize;										//每页显示多少条
		private $limit;											//limit
		private $page;											//当前页码
		private $pagenum;										//总页码
		private $url;											//地址
		private $bothnum;										//两边保持数字分页的量
		
		//构造方法初始化
		public function __construct($total, $pagesize) {
			$this->total = $total ? $total : 1;
			$this->pagesize = $pagesize;
			$this->pagenum = ceil($this->total / $this->pagesize);
			$this->page = $this->setPage();
			$this->limit = "LIMIT ".($this->page-1)*$this->pagesize.",$this->pagesize";
			$this->url = $this->setUrl();
			$this->bothnum = 2;
		}
		
		//拦截器
		public function __get($_key) {
			return $this->$_key;
		}
		
		//获取当前页码
		private function setPage() {
			if (!empty($_GET['page'])) {
				if ($_GET['page'] > 0) {
					if ($_GET['page'] > $this->pagenum) {
						return $this->pagenum;
					} else {
						return $_GET['page'];
					}
				} else {
					return 1;
				}
			} else {
				return 1;
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

		//数字目录
		private function pageList() {
			$pagelist = "";

			for ($i = $this->bothnum; $i >= 1; $i--) {
				$page_code = $this->page-$i;
				if ($page_code < 1) continue;
				$pagelist .= ' <a href="'.$this->url.'&page='.$page_code.'">'.$page_code.'</a> ';
			}

			$pagelist .= ' <a class="page_on" >'.$this->page.'</a> ';
			
			for ($i = 1; $i <= $this->bothnum; $i++) {
				$page_code = $this->page + $i;
				if ($page_code > $this->pagenum) break;
				$pagelist .= ' <a href="'.$this->url.'&page='.$page_code.'">'.$page_code.'</a> ';
			}
			return $pagelist;
		}
		
		//首页
		private function first() {
			if ($this->page > $this->bothnum + 1) {
				return ' <a href="'.$this->url.'">1</a> <a class="page_ellipsis">...</a>';
			}
			return "";
		}
		
		//上一页
		private function prev() {
			if ($this->page == 1) {
				return '<a class="page_prev">上一页</a>';
			}
			return ' <a href="'.$this->url.'&page='.($this->page-1).'" class="page_prev">上一页</a> ';
		}
		
		//下一页
		private function next() {
			if ($this->page == $this->pagenum) {
				return '<a class="page_next">下一页</a>';
			}
			return ' <a href="'.$this->url.'&page='.($this->page+1).'" class="page_next">下一页</a> ';
		}
		
		//尾页
		private function last() {
			if ($this->pagenum - $this->page > $this->bothnum) {
				return ' <a class="page_ellipsis">...</a><a href="'.$this->url.'&page='.$this->pagenum.'">'.$this->pagenum.'</a> ';
			}
		}
		
		//分页信息
		public function show() {
			$page_result = "";

			$page_result .= $this->prev();
			$page_result .= $this->first();
			$page_result .= $this->pageList();
			$page_result .= $this->last();
			$page_result .= $this->next();

			return $page_result;
		}
	}
?>