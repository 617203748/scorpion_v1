<?php
	/**
 	* 功能说明：<excel文档方法类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 开发团队：郭永恩，李鹏
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class Excel
	{
		//网站前台入口
		//static private $action = null;
		//总部后台入口

		
	
		//public static function alertBack($info)

		// 导出excel文档
		public static function exportExcel($letter,$tableheader,$data,$excelname)
		{
			//创建对象
			$excel = new PHPExcel();

			//Excel表格式,这里简略写了8列
			//$letter = array('A','B','C','D','E','F','F','G','H','I','J','K','L','M','N','O','P','Q');
			//表头数组
			//$tableheader = array('学号','姓名','性别','年龄','班级');
			
			//$tableheader = array('id','武器装备名称','入库数量','当前数量','入库人','入库时间','修改时间','武器装备类型','状态','供应单位');

			//填充表头信息
			for($i = 0;$i < count($tableheader);$i++) 
			{
				// 所有单元格默认高度
                $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(15);
                // 所有单元格默认宽度
                $excel->getActiveSheet()->getDefaultColumnDimension()->setWidth(20);

				// 第一行的默认高度
				//$excel->getActiveSheet()->getRowDimension()->setRowHeight(30);
				$excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");
			}

			//表格数组
			/*$data = array(
			array('1','小王','男','20','100'),
			array('2','小李','男','20','101'),
			array('3','小张','女','20','102'),
			array('4','小赵','女','20','103')
			);*/
            
            // 获取搜索的入库信息
			//$data = $this->model->getAllStoreExcel();


			//填充表格信息
			for ($i = 2;$i <= count($data) + 1;$i++) 
			{
				$j = 0;
				foreach ($data[$i - 2] as $key=>$value) 
				{
					$excel->getActiveSheet()->setCellValue("$letter[$j]$i","$value".'  ');
					$j++;
				}
			}


			//创建Excel输入对象
			$write = new PHPExcel_Writer_Excel5($excel);
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
			header("Content-Type:application/force-download");
			header("Content-Type:application/vnd.ms-execl");
			header("Content-Type:application/octet-stream");
			header("Content-Type:application/download");

			header('Content-Disposition:attachment;filename="'.$excelname.'.xls"');
			//header('Content-Disposition:attachment;filename="testdata.xls"');
			header("Content-Transfer-Encoding:binary");
			$write->save('php://output');

		}

	}
?>