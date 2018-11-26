<?php
	/**
 	* 功能说明：<文件工厂方法类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：太原锐意鹏达科技有限公司
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class File {
		private $error;			//错误代码
		private $maxsize;		//表单最大值
		private $type;				//类型
		private $typeArr = array('application/octet-stream','text/plain');		//类型合集
		private $extTypeArr = array('json','eme','zip');		//类型合集
		private $path;				//目录路径
		private $name;			//文件名
		private $tmp;				//临时文件
		private $updir;  //放置图片的文件夹路径
		//构造方法，初始化
		public function __construct($_file,$_maxsize,$path) {

			$this->error = $_FILES[$_file]['error'];
			$this->maxsize = $_maxsize/1024;
			$this->type = $_FILES[$_file]['type'];
			$this->path =$path;
			$this->name = $_FILES[$_file]['name'];
			$this->tmp = $_FILES[$_file]['tmp_name'];


			$this->checkError();
			$this->checkType();
			$this->checkPath();
			$this->moveUpload();
		}
		
		
		//返回路径
		public function getPath()
		{


			//$_path = $_SERVER["SCRIPT_NAME"];

			
			//$_dir = dirname(dirname($_path));
			//
			//是否需要真加？？？？
			//if ($_dir == '\\') $_dir = '/';
			
			//return $this->path = $_dir.$this->path;
			
			//return $this->path = str_replace('/upload', 'upload', $this->path);
			//
			return $this->path;

		}

			//拦截器(__set)
		public function __set($key, $value)
		{
			$this->$key = Tool::mysqlString($value);
		}
			
			//拦截器(__get)
		public function __get($key) 
		{
				return $this->$key;
		}
		
		//移动文件
		private function moveUpload() {
			if (is_uploaded_file($this->tmp)) {

				if (!move_uploaded_file($this->tmp,$this->setNewName())) {

					Tool::alertBack('警告：上传失败！');
				}
				
			} else {
				Tool::alertBack('警告：临时文件不存在！');
			}
		}
		
		//设置新文件名
		private function setNewName() {
			$_nameArr = pathinfo($this->name);

			$_postfix = $_nameArr['extension'];//取文件的后缀
			$_filename = $_nameArr['filename'];//取文件的后缀
			//$_newname = date('YmdHis').mt_rand(100,1000).'_source.'.$_postfix;
			$_newname = date('YmdHis').$_filename.'.'.$_postfix;
		
			$this->path=$this->path.$_newname;

			return ROOT_PATH.$this->path;
		}
		
		//验证目录
		private function checkPath() {
			if (!is_dir(ROOT_PATH.$this->path) || !is_writeable(ROOT_PATH.$this->path)) {
				if (!@mkdir(ROOT_PATH.$this->path,0777,true)) {
					Tool::alertBack('警告：主目录创建失败！');
				}
			}
		
		}
		
		//验证类型
		private function checkType()
		 {

		 	if($this->mimeType())
		 	{
		 		return;
		 	}
		 	if($this->extType())
		 	{
		 		return;
		 	}
		 	Tool::alertBack('警告：该文件类型不合法！');
		}

		private function mimeType()
		{
			if (!in_array($this->type,$this->typeArr)) 
			{
				
				//Tool::alertBack('警告：该图片类型不合法！');
				return false;
			}
			return true;

		}

		private function extType()
		{

			$_nameArr = pathinfo($this->name);
			$ext = $_nameArr['extension'];//取文件的后缀
			if (!in_array($ext,$this->extTypeArr)) {
				
				//Tool::alertBack('警告：该图片类型不合法！');
				return false;
			}
			return true;
		}
		
		//验证错误
		private function checkError() {
			if (!empty($this->error)) {
				switch ($this->error) {
					case 1 :
						Tool::alertBack('警告：上传值超过了约定最大值！');
						break;
					case 2 :
						Tool::alertBack('警告：上传值超过了'.$this->maxsize.'KB！');
						break;
					case 3 :
						Tool::alertBack('警告：只有部分文件被上传！');
						break;
					case 4 :
						Tool::alertBack('警告：没有任何文件被上传！');
						break;
					default:
						Tool::alertBack('警告：未知错误！');
				}
			}
		}
	}
?>