<?php
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		ini_set('display_startup_errors', TRUE);
		date_default_timezone_set('Europe/London');

		define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');

	//**** 使用说明 ****//
	
	//获取excel实例
	/*$objexcel = ExportExcel::getInstace();

	//需要插入excel的数据
	$Data = array('hello','world','ces','cesdd');
	$Data1 = array('hello1','world1','ces1','cesdd');

	//设置xls文件的路径
	ExportExcel::SetPath("d:/excel.xls");


	//循环插入数据
	$i = 1;
	foreach($Data1 as $value)
	{
		$objexcel->setActiveSheetIndex(0)
		            ->setCellValue('A'.$i, $value)
		            ->setCellValue('B'.$i, $value);
		            $i++;
	}

	//导出excel文件
	if(ExportExcel::ExportXls())
	{
		echo "导出EXCEL成功";
	}*/
	/////////////////////////////////////



	class ExportExcel
	{


		private static $savepath = "c://excel.xls";
		//excel对象
		private static $objexcel = null;

		public function __construct()
		{  	  
	       parent::__construct();
		}


		public static function getInstace()
		{
			if(self::$objexcel == null)
			{
				self::$objexcel = new PhpExcel();
				self::$objexcel->getProperties()->setCreator("libin")
							 ->setLastModifiedBy("kaijinsuo")
							 ->setTitle("Excel Document")
							 ->setSubject("Excel Document")
							 ->setDescription("Excel");
			}
			return self::$objexcel;
		}

		

		//导出xls文件
		public static function ExportXls()
		{
			try
			{
				//导出excel的名称 excel5   xls格式  默认为C盘
				$objWriter = PHPExcel_IOFactory::createWriter(self::$objexcel, 'Excel5');
				ob_end_clean();
				header("Content-Type: application/force-download");  
				header("Content-Type: application/octet-stream");  
				header("Content-Type: application/download");  
				header('Content-Disposition:attachment;filename="报表.xls"');  
				header("Content-Transfer-Encoding: binary");  
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");  
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");  
				header("Pragma: no-cache");  
				$objWriter->save('php://output'); 
				//$objWriter->save(self::$savepath);
				return true;
			}
			catch(Exception $e)
			{
				return false;
			}
		}

		//设置导出的excel路径
		public static function SetPath($path)
		{
			self::$savepath = $path;
		}
	}
?>