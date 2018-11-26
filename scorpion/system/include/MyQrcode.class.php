<?php
	/**
 	* 功能说明：<二维码生成方法类>
 	* ============================================================================
	* 版权所有：北京北门外科技有限公司
	* ----------------------------------------------------------------------------
	* 开发团队：维真技术部
	* ----------------------------------------------------------------------------
	*/
    if(!defined('RYPDINC')) exit("Request Error!!!");

    class MyQrcode {

	    //QRcode::png('http://webapp.bmkook.com');
	    
	    /*
		*    L-默认：可以识别已损失的7%的数据 
		*    M-可以识别已损失15%的数据 
		*    Q-可以识别已损失25%的数据 
		*    H-可以识别已损失30%的数据 
		*/

    	// 直接生成二维码
		public static function qrcode($url)
		{
			return QRcode::png($url);
		}


		// 直接生成二维码  多参数
		/*
		 *   $url；地址；
		 *   $outfile：是否输出二维码图片文件，默认否(false)
		 *   $level:容错率，
		 *   $size:生成图片大小，默认4 
		 *   $margin:二维码周围边框空白区域间距值，默认4
		 *   $saveprint：是否保存二维码并显示 m默认false
		 */
		public static function qrcodeMoreParam($url,$outfile,$level='H',$size=12,$margin=4,$saveprint=true)
		{
			$outfile=URI.$outfile;
			return QRcode::png($url,$outfile,$level,$size,$margin,$saveprint);
	
		}



		// 生成带logo的二维码
		public static function logoQrcode($url)
		{
			//return QRcode::png($url);
			$value = 'http://webapp.bmkook.com';

			/*
				 L-默认：可以识别已损失的7%的数据 
			*    M-可以识别已损失15%的数据 
			*    Q-可以识别已损失25%的数据 
			*    H-可以识别已损失30%的数据 
			*/
		
			$errorCorrectionLevel = 'H';//容错级别 
			$matrixPointSize = 9;//生成图片大小 

			//生成二维码图片 
			QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2); 
			$logo = 'logo.png';//准备好的logo图片 
			$QR = 'qrcode.png';//已经生成的原始二维码图 
			 
			if ($logo !== FALSE) 
			{ 
				 $QR = imagecreatefromstring(file_get_contents($QR)); 
				 $logo = imagecreatefromstring(file_get_contents($logo)); 
				 $QR_width = imagesx($QR);//二维码图片宽度 
				 $QR_height = imagesy($QR);//二维码图片高度 
				 $logo_width = imagesx($logo);//logo图片宽度 
				 $logo_height = imagesy($logo);//logo图片高度 
				 $logo_qr_width = $QR_width / 5; 
				 $scale = $logo_width/$logo_qr_width; 
				 $logo_qr_height = $logo_height/$scale; 
				 $from_width = ($QR_width - $logo_qr_width) / 2; 

				 //重新组合图片并调整大小 
				 imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
				 $logo_qr_height, $logo_width, $logo_height); 
			} 

			//输出图片 
			Header("Content-type: image/png");
			ImagePng($QR);
		}

    }
?>