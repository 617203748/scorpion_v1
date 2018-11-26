<?php
	/**
 	* 功能说明：<工厂方法类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：太原锐意鹏达科技有限公司
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class Image {
		
		private $file;				//图片地址
		private $type;				//图片类型
		private $source_width;
		private $source_height;
		private $water_file;//水印图全路径
		private $newfile;

		//构造方法，初始化
		public function __construct($_file,$newfile) {
			//$this->file =$_SERVER["DOCUMENT_ROOT"].$_file;
			//$this->newfile=$_SERVER["DOCUMENT_ROOT"].$newfile;
			$this->file = ROOT_PATH.$_file;
			$this->newfile = ROOT_PATH.$newfile;
			
			// 文件是否存在
			
			if ((!file_exists($this->file)) || (!is_file($this->file)))
			{
				Tool::alertBack('图象文件不存在！');
			}
			list($this->source_width, $this->source_height, $this->type) = getimagesize($this->file);
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
		 /**
		 * 为图片增加水印
		 *
		 * @param       string      filename            原始图片文件名，包含完整路径
		 * @param       string      target_file         需要加水印的图片文件名，包含完整路径。如果为空则覆盖源文件
		 * @param       int         $watermark_place    水印位置代码
		 * @return      mix         如果成功则返回文件路径，否则返回false
		 */
		public function add_watermark($water_file='',$watermark_place='5')
		{ 
			list($this->source_width, $this->source_height, $this->type) = getimagesize($this->file);
			// 文件是否存在
			if ((!file_exists($water_file)) || (!is_file($water_file)))
			{
				Tool::alertBack('图象文件不存在！');
			}
			//1)获得水印文件的信息，并创建水印图资源句柄
			list($water_width, $water_height, $water_type)    = @getimagesize($water_file);
			$water_img   = $this->getFromImg($water_file, $water_type);
			if (!$water_img)
			{
				Tool::alertBack('水印资 源句柄不存在！');
			}
			
			
			
			//2)获得原始图片的操作句柄
			list($this->source_width, $this->source_height) = getimagesize($this->file);

			$source_img  = $this->getFromImg($this->file, $this->type);//3代表png类型图
						
			if (!$source_img)
			{
				Tool::alertBack('原始图资 源句柄不存在！');
			}
			
			//3)根据系统设置获得水印的位置
			switch ($watermark_place)
			{
				case '1':	//左上
					$x = 0;
					$y = 0;
					break;
				case '2':	//右上
					$x = $this->source_width - $water_width;
					$y = 0;
					break;
				case '4':	//左下
					$x = 0;
					$y = $this->source_height - $water_height;
					break;
				case '5':	//右下
					$x = $this->source_width - $water_width;
					$y = $this->source_height - $water_height;
					break;
				default:	//中心
					$x = $this->source_width/2 - $water_width/2;
					$y = $this->source_height/2 - $water_height/2;
			}
			//4)添加水印
		   imageAlphaBlending($water_img, true);
		   imagecopy($source_img, $water_img, $x, $y, 0, 0,$water_width, $water_height);
		  
			//图象输出
			$this->out($source_img,$water_img);
			
		}
		public function add_watermark_zp($water_file='',$watermark_place='5')
		{ 
			list($this->source_width, $this->source_height, $this->type) = getimagesize($this->file);
			// 文件是否存在
			if ((!file_exists($water_file)) || (!is_file($water_file)))
			{
				Tool::alertBack('图象文件不存在！');
			}
			//1)获得水印文件的信息，并创建水印图资源句柄
			list($water_width, $water_height, $water_type)    = @getimagesize($water_file);
			$water_img   = $this->getFromImg($water_file, $water_type);
			if (!$water_img)
			{
				Tool::alertBack('水印资 源句柄不存在！');
			}
			
			
			//2)获得原始图片的操作句柄
			list($this->source_width, $this->source_height) = getimagesize($this->file);
			$source_img  = $this->getFromImg($this->file, 3);//3代表png类型图
			if (!$source_img)
			{
				Tool::alertBack('原始图资 源句柄不存在！');
			}
			
			//3)根据系统设置获得水印的位置
			switch ($watermark_place)
			{
				case '1':
					$x = 0;
					$y = 0;
					break;
				case '2':
					$x = $this->source_width - $water_width;
					$y = 0;
					break;
				case '4':
					$x = 0;
					$y = $this->source_height - $water_height;
					break;
				case '5':
					$x = $this->source_width - $water_width;
					$y = $this->source_height - $water_height;
					break;
				default:
					$x = $this->source_width/2 - $water_width/2;
					$y = $this->source_height/2 - $water_height/2;
			}
			//4)添加水印
		   imageAlphaBlending($water_img, true);
		   imagecopy($source_img, $water_img, $x, $y, 0, 0,$water_width, $water_height);
		  
			//图象输出
			$this->out($source_img,$water_img);
			
		}
		
		//缩略图(固定长高容器，图像等比例，扩容填充，裁剪)[固定了大小，不失真，不变形]
		public function thumb($box_width = 0,$box_height = 0) {
			list($this->source_width, $this->source_height, $this->type) = getimagesize($this->file);
				
			//0) 原始图片以及缩略图的尺寸比例 */
			$scale_org= $this->source_width / $this->source_height;
			/* 处理只有缩略图宽和高有一个为0的情况，这时背景和缩略图一样大 */
			if ($box_width == 0)
			{
				$box_width = $box_height * $scale_org;
				
			}
			if ($box_height == 0)
			{
				$box_height = $box_width / $scale_org;

			}
			//1)创建容器图资源句柄
			$img_box  = imagecreatetruecolor($box_width, $box_height);
			if (!$img_box)
			{
				Tool::alertBack('容器资源句柄不存在！');
			}
			//2)创建容器背景色
			$bg_white = imagecolorallocate($img_box, 255, 255, 255);
			imagefill($img_box,0,0,$bg_white);
			
			//3)计算原始图相对于容器的缩放比例
			if ($this->source_width / $box_width > $this->source_height / $box_height)
			{
				$scale_width  = $box_width;
				$scale_height  = $box_width / $scale_org;
			}
			else
			{
				/* 原始图片比较高，则以高度为准 */
				$scale_width  = $box_height * $scale_org;
				$scale_height = $box_height;
			}
			//4)计算缩放图在容器中的坐标
			$point_x = ($box_width  - $scale_width)  / 2;
			$point_y = ($box_height - $scale_height) / 2;
			
			//5)创建原始图资句柄
			$img_org= $this->getFromImg($this->file,$this->type);
			if (!$img_org)
			{
				Tool::alertBack('原始图资源句柄不存在！');
			}
			imagecopyresampled($img_box, $img_org, $point_x, $point_y, 0, 0, $scale_width, $scale_height, $this->source_width, $this->source_height);

			//6)输出图象
		   
			$this->out($img_box, $img_org);
		}

		//裁剪图片(参数：起始x坐标,超始y坐标,裁剪width宽度，裁剪高度)
		public function cutImage($start_x,$start_y,$width,$height){
			//1.获取原始图信息
			list($this->source_width, $this->source_height, $this->type) = getimagesize($this->file);
			
			//2.创建新图资源
			$new_img = imagecreatetruecolor($width,$height);
			//3.创建来源图资源
			$source_img = $this->getFromImg($this->file,$this->type);
			//4. 裁剪图片
			imagecopyresampled($new_img,$source_img,0,0,$start_x,$start_y,$width,$height,$width,$height);
			//5.输出图像
			$this->out($new_img, $source_img);
		}

		
		//加载图片，各种类型，返回图片的资源句柄
		private function getFromImg($_file, $_type) {
			/*	
			$_type = image_type_to_mime_type($_type);
			switch($imageType) {
				case "image/gif":
					$img=imagecreatefromgif($_file); 
					break;
				case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$img=imagecreatefromjpeg($_file); 
					break;
				case "image/png":
				case "image/x-png":
					$img=imagecreatefrompng($_file); 
					break;
				default:
					Tool::alertBack('警告：此图片类型本系统不支持！');
			}
			*/
			switch ($_type) {
				case 1 :
					$img = imagecreatefromgif($_file);
					break;
				case 2 :
					$img = imagecreatefromjpeg($_file);
					break;
				case 3 : 
					$img = imagecreatefrompng($_file);
					break;
				default:
					Tool::alertBack('警告：此图片类型本系统不支持！');
			}
			return $img;
		}
		
		//图像输出
		public function out(&$new,&$old) {

			imagepng($new,$this->newfile);
			imagedestroy($new);
			imagedestroy($old);
		}
	}
?>