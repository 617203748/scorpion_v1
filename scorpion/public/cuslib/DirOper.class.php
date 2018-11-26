<?php
	/**
 	* 功能说明：<生成目录控制器基类>
 	* ============================================================================
 	* 版权所有：kook公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
 	* 作者：kook技术部
	*/

	class DirOper
	{
		//  生成目录，没有直接生成，有则不再重复生成
		public static function createDir($dirPath='')
		{
			 $dirArr=explode('/',$dirPath);//'upload/aa/bb/cc/dd/aa/bb/cc/dd');
			 $tempDir='';
            foreach($dirArr as $dir)
            {	
        		$tempDir.=$dir.'/';
				if (!is_dir(URI.$tempDir))
			    {
			    	 mkdir(URI.$tempDir,0777,true);
			    }
	 			
            }
		}
		
	}

?>