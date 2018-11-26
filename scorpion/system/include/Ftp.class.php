<?php
	class Ftp
	{

		private $ftp_hostname=FTP_HOST;
		private $ftp_port=FTP_PORT;
		private $ftp_user=FTP_USER;
		private $ftp_pass=FTP_PWD;

		private $ftp_dir='/';
    //ftp资源
		private $ftp_r='';
	
    private $debug=true;
		/**
     * [__construct 构造函数]
     * @param array $config [description]
     */
		public function __construct($config=array())
		{

			$this->init($config);

			if(false===($this->ftp_r=ftp_connect($this->ftp_hostname,$this->ftp_port)))
			{
        if($this->debug === true) 
        {
         $this->error("ftp 连接错误!");
        }
        return false;
			}
      if(ftp_login($this->ftp_r,$this->ftp_user,$this->ftp_pass))
      {
        if(!empty($this->ftp_dir))
        {
           ftp_chdir($this->ftp_r,$this->ftp_dir);
        }
        //启动被动模式
        ftp_pasv($this->ftp_r,true);
      }
      else
      {
        if($this->debug === true) 
        {
         $this->error("ftp 登录错误!");
        }
        return false;
      }
      return true;
		}

   /**
    * [init FTP成员变量初始化]
    * @param  array  $config [description]
    * @return [type]         [description]
    */
  private function init($config = array())
   {
       if(count($config)>0)
       {
         //特殊字符过滤
        $this->ftp_hostname=preg_replace('|.+?://|','',$config['hostname']);//$config['hostname'];
        $this->ftp_user=isset($config['user']) ? $config['user']:$this->ftp_user;
        $this->ftp_pass=isset($config['pass']) ? $config['pass']:$this->ftp_pass;
        $this->ftp_port=isset($config['port']) ? $config['port']:$this->ftp_port;
        $this->ftp_dir=isset($config['dir']) ? $config['dir']:$this->ftp_dir; 
      }

   }

   
  

		 /**
      * [cd 切换目录]
      * @param  [type] $dir [description]
      * @return [type]      [description]
      */
     public function cd($dir)
     {
        if($dir == '' || ! $this->isConn())
        {
          return FALSE;
        }
        return ftp_chdir($this->ftp_r, $dir) ? true : false;
     }
      /**
       * [pwd 返回当前路劲]
       * @return [type] [description]
       */
     public function pwd()
     {
         return ftp_pwd($this->ftp_r);
     }

public function mkdir($dir)
{
    if($this->create_ftp_dir($dir))
    {
         $this->cd($dir);
    }

}
//创建目录
public function create_ftp_dir($dir)
{
    //  如果能进入当前目录或新建成功则返回true  在这里 '或上' 这里建立第一个目录 || @ftp_mkdir($this->ftp_r, $dir) 是为了在已有的目录下 在添加新目录是出错
    if ($this->ftp_is_dir($dir) )
    {
        return true;
    } 
    //自己调用自己
    if (!$this->create_ftp_dir(dirname($dir)))
    {
        return false;
    } 
    // 终结递归并创建目录
   return ftp_mkdir($this->ftp_r, $dir);
}

public function ftp_is_dir($dir)
{
   //获取当前路径 在外部调用的时候
   $original_directory = ftp_pwd($this->ftp_r);
   // 测试是否能改变到要兴建的目录
   // 如果有错误就要不是一个文件要不是一个目录
  //echo $dir.'<br/>'; 
   if (@ftp_chdir($this->ftp_r,$dir)) {
       // 改变当前路径为跟目录
      ftp_chdir($this->ftp_r, $original_directory);
       return true;
   } 
   else 
   {
       return false;
   }
}
    //   /**
    //    * [mkdir 创建目录]
    //    * @param  [type] $path        [description]
    //    * @param  [type] $permissions [description]
    //    * @return [type]              [description]
    //    */
    // public function mkdir($path='',$permissions=null) 
    //  {
    //     if($path == '' || ! $this->isConn())
    //       {
    //         return false;
    //       }
          
    //       $arrDir= explode('/',$path); 
         
    //       //此处是用循环实现的
    //       if(is_array($arrDir))
    //       {
    //           $path='';

    //           foreach($arrDir as $k=>$v)
    //           {  
                  
    //                   $path.='/'.$v;

    //                   $result = @ftp_mkdir($this->ftp_r,$v);
    //                  print_r($this->ftppwd());
                   
    //                   if($result)
    //                   {   //print_r( $result.'yes');
    //                       $this->cd($v);
    //                   }
                    


                 
    //           }
    //       }
    //      // $result = @ftp_mkdir($this->ftp_r,$path);
           
    //       if($result === false) 
    //       {
    //           if($this->debug === true) 
    //           {
    //               $this->error('ftp 新建目录错误:dir['.$path.']');
    //           }
    //           return false;
    //       }
    //       if(!is_null($permissions))
    //       {
    //           $this->chmod($path,(int)$permissions);
    //       }
    //       return true;
          
    //  }
      /**
       * [delete_dir 删除目录]
       * @param  [type] $path [删除目录 路径]
       * @return [type]       [description]
       */
      public function delete_dir($path) 
      {
         if(!$this->isConn())
         {
            return false;
         }
         //对目录宏的'/'字符添加反斜杠'\'
        $path = preg_replace("/(.+?)\/*$/", "\\1/", $path);
       
        //获取目录文件列表
        $filelist = $this->filelist($path);
        
        if($filelist !== false AND count($filelist) > 0) 
        {
          foreach($filelist as $item) 
          {
            //如果我们无法删除,那么就可能是一个文件夹
            //所以我们递归调用delete_dir()
            if(!@$this->delete_file($item))
            {
              $this->delete_dir($item);
            }
          }
        }
        
        //删除文件夹(空文件夹)
        $result = @ftp_rmdir($this->ftp_r, $path);
        
        if($result === false)
        {
          if($this->debug === true)
          {
            $this->error("ftp 目录删除错误:dir[".$path."]");
          }
          return false;
        }
    
        return true;
      }
      /**
       * [delete_file 删除文件]
       * @param  [type] $file [description]
       * @return [type]       [description]
       */
     public function delete_file($file)
     {
       
        if(!$this->isConn()) 
        {  
            return false;
        }

        return @ftp_delete($this->ftp_r,$file) ? true : false;
     }

		/**
     * [upload 上传]
     * @param  [type] $localFile   [本地文件路径]
     * @param  string $remoteFile  [远程路径]
     * @param  string $mode        [上传方式]
     * @param  [type] $permissions [是否改变目录权限]
     * @return [type]              [description]
     */
    public  function upload($localFile, $remoteFile = '',$permissions = NULL) 
    {
         if(!$this->isConn())
         {
          return false;
         }
         if( ! file_exists($localFile))
         {
            if($this->debug === true) 
            {
              $this->error("ftp_no_source_file:".$localFile);
            }
            return false;
        }
         $mode = FTP_BINARY;
         if ($remoteFile == '') 
         {
            $remoteFile = end(explode('/', $localFile));
         }

         $res = @ftp_nb_put($this->ftp_r, $remoteFile, $localFile, $mode);

         while ($res == FTP_MOREDATA) 
         {
            $res= ftp_nb_continue($this->ftp_r);
         }

         if ($res == FTP_FAILED) 
         {
            if($this->debug === true)
            {
              $this->error('ftp 上传错误:localFile['.$localFile.']/remoteFile['.$remoteFile.']');
            }
            return false;
         }
         //是否改变权限
         if(!is_null($permissions)) 
         {
          $this->chmod($remoteFile,(int)$permissions);
         }
         return true;
      }

		  /**
       * [download 下载文件]
       * @param  [type] $remoteFile [description]
       * @param  string $localFile  [description]
       * @param  string $mode       [description]
       * @return [type]             [description]
       */
     public function download($remoteFile, $localFile = '')
     {
         if(!$this->isConn())
         {
          return false;
         }
         
         $mode = FTP_BINARY; 
         if ($localFile == '')
         {
            $localFile = end(explode('/', $remoteFile));
         }
         if (@ftp_get($this->ftp_r, $localFile, $remoteFile, $mode))
         {
            $flag = true;
         }
         else
         {
            $flag = false;
         }
         return $flag;
      }

      /**
       * [rename 重命名/移动]
       * @param  [type]  $oldname [description]
       * @param  [type]  $newname [description]
       * @param  boolean $move    [description]
       * @return [type]           [description]
       */
    public function rename($oldname, $newname, $move = false)
    {
        if(!$this->isConn())
        {
          return false;
        }
    
        $result = @ftp_rename($this->ftp_r, $oldname, $newname);
        
        if($result === false) 
        {
          if($this->debug === true)
          {
            $msg = ($move == false) ? "ftp 重命名错误" : "ftp 移动错误";
            $this->error($msg);
          }
          return false;
        }
        
        return true;
    }

	  /**
     * [size 文件大小]
     * @param  [type] $file [description]
     * @return [type]       [description]
     */
     public function size($file) 
     {
         return ftp_size($this->ftp_r, $file);
      }
     /**
      * [isFile 判断文件是否存在]
      * @param  [type]  $file [description]
      * @return boolean       [description]
      */
    public function isFile($file)
    {
         if ($this->size($file) >= 0)
         {
            return true;
         } 
         else 
         {
            return false;
         }
    }
      /**
       * [fileTime 文件时间]
       * @param  [type] $file [description]
       * @return [type]       [description]
       */
     public function fileTime($file)
     {
         return ftp_mdtm($this->ftp_r, $file);
    }

      /**
       * [filelist 列出当前ftp的目录列表]
       * @param  string $dir [description]
       * @return [type]      [description]
       */
     public function filelist($dir = '.')
     {
        if(!$this->isConn()) 
        {
            return false;
        }

        return ftp_nlist($this->ftp_r,$dir);

     }

      /**
       * [close 关闭ftp]
       * @return [type] [description]
       */
     public function close()
     {
        if(!$this->isConn()) 
         {
            return false;
        }
        return @ftp_close($this->ftp_r);
     }

      public function ftppwd()
      {
         if(!$this->isConn()) 
         {
            return false;
        }
        return @ftp_pwd($this->ftp_r);
      }
    
  /**
   * [chmod 修改文件权限]
   * @param  [type] $path [路径]
   * @param  [type] $perm [权限 如0775]
   * @return [type]       [description]
   */
  public function chmod($path, $perm) 
  {
    if( ! $this->isConn()) 
    {
      return false;
    }
    
    //只有在PHP5中才定义了修改权限的函数(ftp)
    if(!function_exists('ftp_chmod'))
     {
      if($this->debug === true) 
      {
        $this->_error("ftp_unable_to_chmod(function)");
      }
      return false;
    }
    
    $result = @ftp_chmod($this->ftp_r,$perm,$path);
    
    if($result === false) 
    {
      if($this->debug === true)
      {
        $this->error("ftp 权限错误:path[".$path."]-chmod[".$perm."]");
      }
      return false;
    }
    return true;
  }
   
   /**
    * [isConn 判断是否连接成功]
    * @return boolean [description]
    */
    private function isConn()
     {
      if(!is_resource($this->ftp_r))
       {
        if($this->debug === true) 
        {
          $this->error("ftp 连接错误!");
        }
        return false;
      }
      return true;
    }
   /**
    * [error 错误日志记录]
    * @param  [type] $msg [description]
    * @return [type]      [description]
    */
    private function error($msg) 
    {
      return @file_put_contents('ftp_err.log', "date[".date("Y-m-d H:i:s")."]-hostname[".$this->hostname."]-username[".$this->username."]-password[".$this->password."]-msg[".$msg."]\n", FILE_APPEND);
    }
	}
?>