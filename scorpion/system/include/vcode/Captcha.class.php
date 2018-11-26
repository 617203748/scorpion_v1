<?php
	/**
 	* 功能说明：<验证码类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：郭永恩，李鹏
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");
	
	class Captcha
	{
		// 验证码取值类型	1:数字  2:英文   3:单词
		public $safe_codetype='';
		// 验证码宽度
		public $safe_width='';
		// 验证码高度
		public $safe_wheight='';
		// 验证码基本配置
		public $config=array();
		// 验证码字符串
		public $rndstring;
 		
 		private $fonts = '';
 		private $words_txt = '';
 		private $words_jpg = '';

		public function __construct()
		{
			$this->fonts = VCODE_PATH.'fonts/ggbi.ttf';
			$this->words_txt = VCODE_PATH.'words/words.txt';
			$this->words_jpg = VCODE_PATH.'words/vdcode.jpg';

			$this->safe_codetype = '1';
			$this->safe_wwidth = '65';
			$this->safe_wheight = '20';
		}

		//初始化所需的参数
		private function init(){	
			$this->config = array(
			    'font_size'   => 14,
			    'img_height'  => $this->safe_wheight,
			    'word_type'  => (int)$this->safe_codetype,   // 1:数字  2:英文   3:单词
			    'img_width'   => $this->safe_wwidth,
			    'use_boder'   => TRUE,
			    'font_file'   => $this->fonts,
			    'wordlist_file'   => $this->words_txt,
			    'filter_type' => 5
			);
		}

	  	//拦截器(__set)
		public function __set($key, $value) {
			$this->$key = $value;
		}
		
		//拦截器(__get)
		public function __get($key) {
			return $this->$key;
		}
		
		//获取验证码
		public function getCode(){
			return strtolower($this->rndstring);
		}
		
		//创建验证码
		public function create(){		
			if ($this->echo_validate_image()){
				// 如果不成功则初始化一个默认验证码
			    $this->rndstring = 'abcd';
			   
			    $im = imagecreatefromjpeg($this->words_jpg);
			    
				header("Pragma:no-cache\r\n");
			    header("Cache-Control:no-cache\r\n");
			    header("Expires:0\r\n");
			    
				imagejpeg($im);
			    imagedestroy($im);
			}
		}
		
		//生成验证码图片
		private function echo_validate_image()
		{	
			if(!function_exists('ob_gzhandler')) ob_clean(); 

		    $this->init();
			
			//主要参数
		    $font_size   = isset($this->config['font_size']) ? $this->config['font_size'] : 14;
		    $img_height  = isset($this->config['img_height']) ? $this->config['img_height'] : 20;
		    $img_width   = isset($this->config['img_width']) ? $this->config['img_width'] : 65;
		    $font_file   = isset($this->config['font_file']) ? $this->config['font_file'] : $this->fonts;
		    $use_boder   = isset($this->config['use_boder']) ? $this->config['use_boder'] : TRUE;
		    $filter_type = isset($this->config['filter_type']) ? $this->config['filter_type'] : 0;
		    
		    //创建图片，并设置背景色
		    $im = @imagecreate($img_width, $img_height);
		    imagecolorallocate($im, 255,255,255);

		    //文字随机颜色
		    $fontColor[]  = imagecolorallocate($im, 0x15, 0x15, 0x15);
		    $fontColor[]  = imagecolorallocate($im, 0x95, 0x1e, 0x04);
		    $fontColor[]  = imagecolorallocate($im, 0x93, 0x14, 0xa9);
		    $fontColor[]  = imagecolorallocate($im, 0x12, 0x81, 0x0a);
		    $fontColor[]  = imagecolorallocate($im, 0x06, 0x3a, 0xd5);
		   
		    //获取随机字符
		    $this->rndstring  = '';
		    if ($this->config['word_type'] != 3)
		    {
		        for($i=0; $i<4; $i++)
		        {
		            if ($this->config['word_type'] == 1)
		            {
		                $c = chr(mt_rand(48, 57));
		            } else if($this->config['word_type'] == 2)
		            { 
		                $c = chr(mt_rand(65, 90));
		                if( $c == 'I' ) $c = 'P';
		                if( $c == 'O' ) $c = 'N';
		            }
		            $this->rndstring .= $c;
		        }
		    } 
			else { 
		        $fp = @fopen($this->config['wordlist_file'], 'rb');
		        if (!$fp) return FALSE;
		
		        $fsize = filesize($this->config['wordlist_file']);
		        if ($fsize < 32) return FALSE;
		
		        if ($fsize < 128) 
		        {
		          $max = $fsize;
		        } else {
		          $max = 128;
		        }
		
		        fseek($fp, rand(0, $fsize - $max), SEEK_SET);
		        $data = fread($fp, 128);
		        fclose($fp);
		        $data = preg_replace("/\r?\n/", "\n", $data);
		
		        $start = strpos($data, "\n", rand(0, 100)) + 1; 
		        $end   = strpos($data, "\n", $start); 
		        $this->rndstring  = strtolower(substr($data, $start, $end - $start)); 
		    }
		    		    		    
		    $rndcodelen = strlen($this->rndstring);
		   
		    //背景横线
		    $lineColor1 = imagecolorallocate($im, 0xda, 0xd9, 0xd1);
		    for($j=3; $j<=$img_height-3; $j=$j+3)
		    {
		        imageline($im, 2, $j, $img_width - 2, $j, $lineColor1);
		    }
		    
		    //背景竖线
		    $lineColor2 = imagecolorallocate($im, 0xda,0xd9,0xd1);
		    for($j=2;$j<100;$j=$j+6)
		    {
		        imageline($im, $j, 0, $j+8, $img_height, $lineColor2);
		    }
		
		    //画边框
		    if( $use_boder && $filter_type == 0 )
		    {
		        $bordercolor = imagecolorallocate($im, 0x9d, 0x9e, 0x96);
		        imagerectangle($im, 0, 0, $img_width-1, $img_height-1, $bordercolor);
		    }
		    
		    //输出文字
		    $lastc = '';
		    for($i=0;$i<$rndcodelen;$i++)
		    {
		        $bc = mt_rand(0, 1);
		        $this->rndstring[$i] = strtoupper($this->rndstring[$i]);
		        $c_fontColor = $fontColor[mt_rand(0,4)];
		        $y_pos = ($i == 0) ? 4 : $i*($font_size+2);
		        $c = mt_rand(0, 15);
		        @imagettftext($im, $font_size, $c, $y_pos, 19, $c_fontColor, $font_file, $this->rndstring[$i]);
		        $lastc = $this->rndstring[$i];
		    }

		    //图象效果
		    switch($filter_type)
		    {
		        case 1:
		            imagefilter ( $im, IMG_FILTER_NEGATE);
		            break;
		        case 2:
		            imagefilter ( $im, IMG_FILTER_EMBOSS);
		            break;
		        case 3:
		            imagefilter ( $im, IMG_FILTER_EDGEDETECT);
		            break;
		        default:
		            break;
		    }
	
		    header("Pragma:no-cache\r\n");
		    header("Cache-Control:no-cache\r\n");
		    header("Expires:0\r\n");
		
		    // 输出特定类型的图片格式，优先级为 gif -> jpg ->png
			// dump(function_exists("imagejpeg"));
		    // ob_clean()关键代码，防止出现'图像因其本身有错无法显示'的问题。
		   
		    if(function_exists("imagegif"))
		    {  	
				// gif图片格式体积较小
				header("content-type:image/gif\r\n");
				imagegif($im);
				// header("content-type:image/jpeg\r\n");
				//imagejpeg($im);
				 
		    }
		    else
		    {
		        header("content-type:image/png\r\n");
		        imagepng($im);
		    }
			
		    imagedestroy($im);
		}
	}
?>