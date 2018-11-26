<?php
	/**
 	* 功能说明：<工厂方法类>
 	* ============================================================================
	* 版权所有：山西蒲公英生活商贸有限公司，并保留所有权利。
	* ----------------------------------------------------------------------------
	* 开发团队：蒲公英技术部
	* ----------------------------------------------------------------------------
	* 日期：2015.05
	*/
if(!defined('RYPDINC')) exit("Request Error!!!");

	class Tool {

		public static $tpl = null;


		//弹窗跳转
		public static function alertLocation($info,$url)
		{
			if (!isset($info) || $info == null || $info =='') {
				echo "<script type='text/javascript'>location.href='$url';</script>";
				exit();
			}
			self::$tpl = TPL::getInstance();
			self::$tpl->assign('info',$info);
			self::$tpl->assign('url_path',$url);
			self::$tpl->display('information.tpl');
			exit();
		}

		//弹窗跳转错误页面   alertErrorPath
		public static function alertError($url)
		{
			if (isset($url))
			{
				$errorPath=ERROR_PATH.$url.'.html';
				echo "<script type='text/javascript'>location.href='$errorPath';</script>";
				exit();
			}
			exit();
		}
		//弹窗返回
		public static function alertBack($info)
		{
			self::$tpl = TPL::getInstance();
			self::$tpl->assign('info',$info);
			self::$tpl->assign('url_path','javascript:history.back();');
			self::$tpl->display('information.tpl');
			exit();

		}

		//弹窗关闭
		public static function alertClose($_info)
		{
			self::$tpl = TPL::getInstance();
			self::$tpl->assign('info',$info);
			self::$tpl->assign('url_path','javascript:close();');
			self::$tpl->display('information.tpl');
			exit();
		}


        //提示弹框(关闭后留在本页面)   王
		/*public static function alertWindow($url=null,$info)
		{
			if($url)
			{
				$info="<a href=$url>$info</a>";
			}
			self::$tpl = TPL::getInstance();
			self::$tpl->assign('info',$info);
            self::$tpl->display('alertwindow.tpl');
            exit;
		}*/

        //当前页面错误提示   王
        /*public static function errorRemind($info)
		{
			self::$tpl = TPL::getInstance();
			self::$tpl->assign('info',$info);
            self::$tpl->display('alertwindow.tpl');
            exit;
		}*/


        //判断会员是否登录--PC端
        public static function checkLogin()
        {
        	if(!self::getSession('vip_vipid') || !self::getSession('is_login'))
            {
               self::jumpPage(WEB_NAME.'?act=vip&m=login');
            }
        }


        //判断商家是否登录
		public static function checkCompanyLogin()
        {

        	if(!self::getSession('company'))
            {
               self::jumpPage(WEB_NAME.'?act=comlogin&m=login');
            }

            if(self::getSession('company') && self::getSession('company')->isLogin==false )
            {
            	self::jumpPage(WEB_NAME.'?act=comlogin&m=login');
            }

        }




        //判断会员是否登录--webApp端
        public static function checkWebAppLogin()
        {
        	if(!self::getSession('vip_vipid') || !self::getSession('is_login'))
            {
               self::jumpPage('?act=vip&m=login');
            }
        }



        //进入模块后，要时刻判断校区cookie中的校区id是否存在
        public static function checkScId()
        {
        	//进入模块时,校区id在cookie和session中已经各自保存了一份
        	$sc_id=Tool::getCookie('sc_id');
		    if(!$sc_id)
		    {
		       $sc_id=Tool::getSession('sc_id');
		       Tool::saveCookie('sc_id',$sc_id);
		    }
		    if(!$sc_id)
		    {
		       $sc_id=$GLOBALS['sc_id'];
		       Tool::saveCookie('sc_id',$sc_id);
		    }
		    if(!$sc_id)
		    {
		       Tool::jumpPage(WEB_NAME);
		    }
		    return $sc_id;
        }

        /*添加修改等验证跳转   传入$this,dto中的验证方法,模板名称*/
        /*public static function getReturnAlert($alert,$action,$tpl)
        {
	        $action->tpl->assign('alert',$alert);
	        $action->tpl->display($tpl);
        }*/


		public static function jumpPage($page)
		{
			header("Location: ".$page);
			exit;
		}

		//返回上一页 无提示信息
		public static function getBack()
		{
			echo"<script>history.go(-1);</script>";
		}


		// 返回上上页 无提示信息
		public static function getBackTwo()
		{
			echo"<script>history.go(-2);</script>";
		}


		// 返回历史第三页 无提示信息
		public static function getBackThree()
		{
			echo"<script>history.go(-3);</script>";
		}

        // 判断id是否为正整数
		public static function isUnsignedInt($id)
		{
			if(preg_match('/^[1-9]\d*$/',$id))
			{
				return $id;
			}
		}

		//解析html标签
		public static function unHtml($str) {
			return htmlspecialchars_decode($str);
		}

		//生成html标签
		public static function htmlString($data) {
			$string = null;

			if (is_array($data)) {
				foreach ($data as $key=>$value) {
					$string[$key] = Tool::htmlString($value);  //递归
				}
			} elseif (is_object($data)) {
				foreach ($data as $key=>$value) {
					$string->$key = Tool::htmlString($value);  //递归
				}
			} else {
				$string = htmlspecialchars($data);
			}
			return $string;
		}

		//数据库输入防注入过滤
		public static function mysqlString($data)
		{
			//如果是LinuxGPC没开启，mysql_real_escape_string替换成addslashes()
		   return !GPC ? addslashes($data) : $data;
		}


		//设置cookie
		public static function saveCookie($key, $value,$day = 365)
		{
			setcookie($key, $value, time() + 3600 * 24 * $day);//,'/','localhost');
		}


		//获取cookie
		public static function getCookie($key)
		{
			if(isset($_COOKIE[$key]))
			{
				if(!empty($_COOKIE[$key]))
				{
					return $_COOKIE[$key];
				}
			}

			return null;
		}

        //设置session
        public static function saveSession($key, $value)
        {
			$_SESSION[$key]=$value;
		}

        //获取session
        public static function getSession($key)
        {

        	if(isset($_SESSION[$key]))
        	{
				if(!empty($_SESSION[$key]))
				{
					return $_SESSION[$key];
				}
			}
			return null;
		}

		//清空cookie
		public static function clearCookie($key,$day = 365)
		{
			if(!empty($_COOKIE[$key]))
			{
				setcookie($key, null, time()- 3600 * 24 * $day);
			}
		}

        //清空所有的session
        public static function clearAllSession()
        {
           session_destroy();
        }

        //清空指定的session
        public static function clearOneSession($key)
        {
           unset($_SESSION[$key]);
        }

        /**
         * 生成编码,默认不足左边补零
         * $id : 需要拼接的id
         * $len : 拼接后长度
         * 例 $id=34,$len=4,生成的id=0034
         */
        public static function SetCode($id,$len)
        {
        	//$len='2';
			$id= sprintf('%0'.$len.'s', $id);
			return $id;
        }

        /**
         *  异或方式生成编码
         *  例：$data=18202864615;data不能为string;
         *      $len=11,则$fix=00000000000, 11个零,
         *
         */
        public static function SetCodeByXOR($data)
        {
        	//echo '$data= ';var_dump($data);echo '<hr/>';
        	$len=strlen($data);
        	$fix= sprintf('%0'.$len.'s', '0');
        	$time=date("YmdHis");
        	$int_time=(int)$time;
        	// 转换成unix时间戳
        	$unix_time=strtotime($time);
            $data=$data ^ $fix.$unix_time;
            //echo '$data= ';var_dump($data);echo '<hr/>';
            return $data;
        }


        /**
		* 产生随机字符串
		*
		* @param    int        $length  输出长度
		* @param    string     $chars   可选的 ，默认为 0123456789
		* @return   string     字符串
		*/
		function random($length, $chars = '0123456789')
		{
			$hash = '';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++)
			{
				$hash .= $chars[mt_rand(0, $max)];
			}
			return $hash;
		}

        //发送邮件  会员  //$member_name,$member_id,$member_email,$activecode,$url,$fromName
        function sendEmail($member_name,$member_id,$member_email,$activecode,$url)
        {
            $mailsubject = '蒲公英官方购物网站提醒: '.$member_name.' 修改密码设置';//邮件主题

            $mailbody = '<h1 style="font-size:18px;font-weight:bold;">尊敬的'.$member_name.':</h1>

				<p style="padding-left:20px;">您好, 您刚才在蒲公英官方购物网站申请了重置密码，请点击下面的链接进行重置：</p>

				<p style="padding-left:40px;">
				    <a href="http://'.$url.'/?act=vip&m=findByEmail&id='.$member_id.'&activecode='.$activecode.'">
				http://'.$url.'/?act=vip&m=findByEmail&id='.$member_id.'&activecode='.$activecode.'
				</a></p>

				<p style="padding-left:20px;">此链接只能使用一次, 如果失效请重新申请. 如果以上链接无法点击，请将它拷贝到浏览器(例如IE)的地址栏中。</p>

				<p style="text-align:right;padding-right:20px;">蒲公英官方购物网站</p>
				<p style="text-align:right;padding-right:20px;">'.date('Y-m-d H:i:s').'</p>';//邮件内容

	        //这里面的一个true是表示使用身份验证,否则不使用身份验证.默认是false
	        $smtp = new Mail(SMTPSERVER,SMTPSERVERPORT,true,SMTPUSERMAIL,SMTPPASS);
	        $smtp->fromName='蒲公英找回密码';
	        $smtp->debug = false;//是否显示发送的调试信息
	        //发送邮件
	        return @$smtp->sendmail($member_email,SMTPUSERMAIL, $mailsubject,$mailbody,SMTPMAILTYPE);
        }

        //发送邮件  商家  //$member_name,$member_id,$member_email,$activecode,$url,$fromName
        function sendComEmail($member_name,$member_id,$member_email,$activecode,$url)
        {
            $mailsubject = '蒲公英官方购物网站提醒: '.$member_name.' 修改密码设置';//邮件主题

            $mailbody = '<h1 style="font-size:18px;font-weight:bold;">尊敬的'.$member_name.':</h1>

				<p style="padding-left:20px;">您好, 您刚才在蒲公英官方购物网站申请了重置密码，请点击下面的链接进行重置：</p>

				<p style="padding-left:40px;">
				    <a href="http://'.$url.'/?act=comlogin&m=emailfind&id='.$member_id.'&activecode='.$activecode.'">
				http://'.$url.'/?act=comlogin&m=emailfind&id='.$member_id.'&activecode='.$activecode.'
				</a></p>

				<p style="padding-left:20px;">此链接只能使用一次, 如果失效请重新申请. 如果以上链接无法点击，请将它拷贝到浏览器(例如IE)的地址栏中。</p>

				<p style="text-align:right;padding-right:20px;">蒲公英官方购物网站</p>
				<p style="text-align:right;padding-right:20px;">'.date('Y-m-d H:i:s').'</p>';//邮件内容

	        //这里面的一个true是表示使用身份验证,否则不使用身份验证.默认是false
	        $smtp = new Mail(SMTPSERVER,SMTPSERVERPORT,true,SMTPUSERMAIL,SMTPPASS);
	        $smtp->fromName='蒲公英找回密码';
	        $smtp->debug = false;//是否显示发送的调试信息
	        //发送邮件
	        return @$smtp->sendmail($member_email,SMTPUSERMAIL, $mailsubject,$mailbody,SMTPMAILTYPE);
        }


        //发送邮件  招聘者  //$member_name,$member_id,$member_email,$activecode,$url,$fromName
        function sendIssuerEmail($member_name,$member_id,$member_email,$activecode,$url)
        {
            $mailsubject = '蒲公英官方购物网站提醒: '.$member_name.' 修改密码设置';//邮件主题

            $mailbody = '<h1 style="font-size:18px;font-weight:bold;">尊敬的'.$member_name.':</h1>

				<p style="padding-left:20px;">您好, 您刚才在蒲公英官方购物网站申请了重置密码，请点击下面的链接进行重置：</p>

				<p style="padding-left:40px;">
				    <a href="http://'.$url.'/?act=issuer&m=emailfind&id='.$member_id.'&activecode='.$activecode.'">
				http://'.$url.'/?act=issuer&m=emailfind&id='.$member_id.'&activecode='.$activecode.'
				</a></p>

				<p style="padding-left:20px;">此链接只能使用一次, 如果失效请重新申请. 如果以上链接无法点击，请将它拷贝到浏览器(例如IE)的地址栏中。</p>

				<p style="text-align:right;padding-right:20px;">蒲公英官方购物网站</p>
				<p style="text-align:right;padding-right:20px;">'.date('Y-m-d H:i:s').'</p>';//邮件内容

	        //这里面的一个true是表示使用身份验证,否则不使用身份验证.默认是false
	        $smtp = new Mail(SMTPSERVER,SMTPSERVERPORT,true,SMTPUSERMAIL,SMTPPASS);
	        $smtp->fromName='蒲公英找回密码';
	        $smtp->debug = false;//是否显示发送的调试信息
	        //发送邮件
	        return @$smtp->sendmail($member_email,SMTPUSERMAIL, $mailsubject,$mailbody,SMTPMAILTYPE);
        }



        //处理上传的文件（非图片）
		public static function localFileUp($sourcefile,$path)
		{
			if($_FILES[$sourcefile]['error'] == 0 && $_FILES[$sourcefile]['size'] > 0)
			{


				//原始图  $path为本地路径
				//$path = UPLOAD_LOCAL_PATH;
                // 51200000 = 50M
				$max_file_size = isset($_POST['MAX_FILE_SIZE']) ? $_POST['MAX_FILE_SIZE'] : 51200000;


				$file_upload = new File($sourcefile,$max_file_size ,$path);

				$file_path = $file_upload->getPath();

				//echo '   file_path= '.$file_path."<br/><br/>";

				return $file_path;
			}
			else
			{
				echo 'error';

				self::alertBack('请选择文件！');
				exit;
			}
		}

		// 验证目录
		public static function checkPath($path)
		{
			if (!is_dir(ROOT_PATH.$path) || !is_writeable(ROOT_PATH.$path))
			{
				if (!@mkdir(ROOT_PATH.$path,0777,true))
				{
					Tool::alertBack('警告：主目录创建失败！');
				}
			}

		}



		//处理上传的图片 进行压缩处理   只能处理一张图片 保存alpha通道
		public static function localPictureUp($sourcepic,$path,$width,$height)
		{
			if($_FILES[$sourcepic]['error'] == 0 && $_FILES[$sourcepic]['size'] > 0)
			{


				//原始图  $path为本地路径
				//$path = UPLOAD_LOCAL_PATH;
                // 51200000 = 50M
				$max_file_size = isset($_POST['MAX_FILE_SIZE']) ? $_POST['MAX_FILE_SIZE'] : 51200000;


				$file_upload = new FileUpload($sourcepic,$max_file_size ,$path);
				//$file_upload = new FileUpload($sourcepic,$_POST['MAX_FILE_SIZE'],$path);
				$source_pic_path = $file_upload->getPath();


				//分割文件路径
				$arr = explode('.', $source_pic_path);

				//压缩后的新图片  目前图片格式为 png
				$new_pic_path = str_replace('_source', '', $arr[0]) . '.png';
				$image_class = new Image($source_pic_path, $new_pic_path);
				$image_class->thumb($width, $height);

				//是否添加水印
				/*if(isset($_POST['is_water'])){

					if(Validate::isEquals(trim($_POST['is_water']), 1)){
						$image_class->file = ROOT_PATH. $new_pic_path_pic_path;
						$image_class->newfile = ROOT_PATH. $new_pic_path_pic_path;
						$image_class->add_watermark(ROOT_PATH. 'config/water/logo.png');
					}
				}*/

				/*删除_source图   upload/20151201093502360_source.jpg  */
                @unlink(ROOT_PATH.$source_pic_path);
				return $new_pic_path;
			}
			else
			{
				echo 'error';

				self::alertBack('请选择图片！');
				exit;
			}
		}



        //处理上传的图片 进行压缩处理   只能处理一张图片 保存alpha通道
		public static function pictureUp($sourcepic,$width,$height)
		{

			if($_FILES[$sourcepic]['error'] == 0 && $_FILES[$sourcepic]['size'] > 0)
			{
				//原始图  $path为本地路径
				$path = UPLOAD_LOCAL_PATH;
				$file_upload = new FileUpload($sourcepic,$_POST['MAX_FILE_SIZE'],$path);
				$source_pic_path = $file_upload->getPath();

				//分割文件路径
				$arr = explode('.', $source_pic_path);

				//压缩后的新图片  目前图片格式为 png
				$new_pic_path = str_replace('_source', '', $arr[0]) . '.png';
				$image_class = new Image($source_pic_path, $new_pic_path);
				$image_class->thumb($width, $height);

				//是否添加水印
				/*if(isset($_POST['is_water'])){

					if(Validate::isEquals(trim($_POST['is_water']), 1)){
						$image_class->file = ROOT_PATH. $new_pic_path_pic_path;
						$image_class->newfile = ROOT_PATH. $new_pic_path_pic_path;
						$image_class->add_watermark(ROOT_PATH. 'config/water/logo.png');
					}
				}*/

				/*删除_source图   upload/20151201093502360_source.jpg  */
                @unlink(SYS_PATH.$source_pic_path);
				return $new_pic_path;
			}
			else
			{
				self::alertBack('请选择图片！');
				exit;
			}
		}

        //生成上传路径
        public function setUploadPath($uniid='',$item_name='')
        {
        	   if(!$uniid && !$item_name)
        	   {
                   $uniid=self::getSession('company')->uniid;
        	       $item_name=self::getSession('company')->projectname;
        	   }


               $ftp = new ftp();
               $foldername=date("Ym");
               $uploadPath=UPLOAD_LOCAL_PATH.Tool::SetCode($uniid,4).'/'.$item_name.'/'.$foldername.'/';   //  upload/0001/mall/201508
               $ftp->mkdir($uploadPath);  //判断文件夹是否存在  不存在就新建

               /*获取201508文件夹下的文件夹,有则取得最大的文件夹,没有就建一个1文件夹*/
               $dirArr=$ftp->filelist();
               if(count($dirArr))
               {
	               	/*过滤201508下的文件,必须是数字，必须是文件夹*/
               	    $newArr=array();
               	    foreach($dirArr as $dir)
               	    {
	               	  	if(preg_match('/^[0-9]*$/', $dir) && $ftp->ftp_is_dir($dir))
	                    {
	                        $newArr[]=$dir;
	                    }
               	    }

               	    /*eg:201508文件夹下没有文件夹  只有图片,直接新建一个文件*/
                    if(!$newArr)
                    {
                       $ftp->mkdir('1');
           	           return $uploadPath.'1/';
                    }

					sort($newArr);/*文件夹排序   1  2  3*/
					$dir=end($newArr);
					$ftp->cd($dir);/*指到最后一个文件夹,列出其下边的文件*/
	                $endDirArr=$ftp->filelist();
	                /*过滤最后一个文件夹下的文件,如果类型是.png的图片大于了100张，则新建一个文件夹*/
                    $picArr=array();
                    foreach($endDirArr as $endDir)
                    {
                       if(preg_match('/\.png$/',$endDir))
                       {
                       	 $picArr[]=$endDir;
                       }
                    }

	                if(count($picArr)>GOODS_FOLDER_PIC_NUM)
	                {
	                    $dir++;
	                    $ftp->cd('..');//指到上一级目录201508
	                    $ftp->mkdir($dir);

	                }
                	//返回当前的目录
                	return $uploadPath.$dir.'/';
               }

           	   $ftp->mkdir('1');
           	   return $uploadPath.'1/';
        }


        //上传图片
        public static function uploadImage($dir,$pic_name)
        {
             if($_FILES[$pic_name]['error']>0 && $_FILES[$pic_name]['size']==0)
             {
             	return;
             }

             if(!empty($_FILES))
             {
                $config=array
                 (
                   'hostname'=>FTP_HOST,
                   'user'=>FTP_USER,
                   'pass'=>FTP_PWD,
                   'port'=>FTP_PORT,
                   'dir'=>$dir
                 );
                 $ftp = new ftp($config);
                 //上传图片
                 if ($_FILES[$pic_name]['tmp_name'])
                 {
                     $localfile=$_FILES[$pic_name]['tmp_name'];
		             $newfile=date('YmdHis').mt_rand(100,1000).'_source.png';
                     $ftpput = $ftp->upload($localfile,$newfile); //FTP上传原图到远程服务器

                     $ftp->close();//关闭FTP连接
                     if(!$ftpput)
                     {
                     	return false;
                     }


                     @unlink($localfile);

                     $picpath=substr($dir,1).$newfile;
                     return $picpath;
                 }
              }
        }


        //上传压缩的图片
        public static function uploadThumbImage($dir,$localpic)
        {

            if(!empty($_FILES))
            {
                $config=array
                (
                   'hostname'=>FTP_HOST,
                   'user'=>FTP_USER,
                   'pass'=>FTP_PWD,
                   'port'=>FTP_PORT,
                   'dir'=>$dir
                );
                $ftp = new ftp($config);

                //上传图片
                $result=$ftp->upload(SYS_PATH.$localpic);
                @unlink(SYS_PATH.$localpic);
                return $result;

	             /*if ($ftp->upload(SYS_PATH.$localpic))
	             {
	                return true;
	             }
	             else
	             {
	             	return false;
	             }*/
            }
        }



        //删除ftp上的图片
        public static function deleteFtpFile($picpath)
        {
        	//删除图片
	        $ftp = new ftp();
	        //删除ftp上的图片
	        $ftp->delete_file($picpath);
        }


        // 给数组分配样式classStyle ---
	    public static function addClass($num,$alljob)
	    {
	        //所有招聘类数组
	        $arr1=array('a','b','c','d','e','f','g','h','i','j','k','l');
	        //最近招聘类数组
	        $arr2=array('c','d','e','f','g','h','i','j','k','l');

	        $str='arr'.$num;
	        $arr=$$str;

	        for($i=1;$i<=ceil(count($alljob)/count($arr));$i++)
	        {
	            foreach(array_slice($arr,0,count($alljob)) as $key=>$value)
	            {
	                $alljob[$key*$i]->classStyle=$value;
	            }
	        }
	        return $alljob;
	    }


        //拼接编码  $id为要拼接的字符  $char是用什么字符拼装  $length是需要的长度
        public static function getCode($id,$char,$length)
        {
           $len=$length-strlen($id);
           for($i=0;$i<$len;$i++)
           {
           	  $id=$char.$id;
           }
           return $id;
        }


         //格式化数组中的值 如 8,16,9,....N
	    public static function getFormatValue($arr,$k)
	    {
	        $result='';
	        foreach($arr as $key=>$value)
	        {
	          $result.=$value->$k.',';
	        }
	        return trim($result,',');
	    }


	    public static function objectToArray($e)
		{
		    $e=(array)$e;
		    foreach($e as $k=>$v)
		    {
		        if( gettype($v)=='resource' ) return;
		        if( gettype($v)=='object' || gettype($v)=='array' )
		            // $e[$k]=(array)objectToArray($v);
		            $e[$k]=(array)self::objectToArray($v);
		    }
		    return $e;
        }


        public static function arrayToObject($e)
        {
		    if( gettype($e)!='array' ) return;
		    foreach($e as $k=>$v)
		    {
		        if( gettype($v)=='array' || getType($v)=='object' )
		           // $e[$k]=(object)arrayToObject($v);
		        	$e[$k]=(object)self::arrayToObject($v);
		    }
		    return (object)$e;
		}

		//替换后2位字符
		public static function replaceSring($str,$length)
		{
			$result = mb_substr($str,0,mb_strlen($str,'utf-8') - $length,'utf-8');
			return $result.str_repeat("*",$length);;
		}

		//测试对象是否已经被实例化
		public static function check($value,$from='')
		{
		       //$from  在哪个方法中使用
		       echo "<pre>";
		       var_dump($value);
               echo '</pre>';
		}

		//跳转到首页
		public static function jump_first()
		{
			if(!isset($_GET['sc_id']) || empty($_GET['sc_id']))
			{
				//此处记得修改为实际域名
				header('Location: '."http://localhost/pugoing");
			}
		}


        //加密
        public static function encode($v)
        {
        	if(isset($_SESSION['TOKEN']))
        	{
        		$str=$_SESSION['TOKEN'].'_'.$v;
            	return base64_encode($str);
        	}
			return ;
 		}


 		//解密
		public static function decode($v)
		{
  			$str=base64_decode($v);
   			$arr=explode('_',$str);
			if(count($arr)>1)
			{
				 return  $arr[1];
			}
		}


        //生成预处理数组
        /* $key=array(
                  $sql,
                  'dtarr'=>array('mall_goods.goodsattr,'mall_setbase.model'),
                  array('id'=>$id)
                )
        */
        public static function getPreArr($sql,$value=array())
        {
           return array('sql'=>$sql,'value'=>$value);
        }




		//生成Token
		public static function setToken($update=false)
		{

			if(!isset($_SESSION['TOKEN']) )
			{
				$token=microtime();
				$_SESSION['TOKEN']=$token;
			}
			if(isset($_SESSION['TOKEN']))
			{
				if(empty($_SESSION['TOKEN']))
				{
					  $token=time();
					$_SESSION['TOKEN']=$token;
				}

				if($update)
				{
					 $token=time();
					$_SESSION['TOKEN']=$token;
				}
			}

		}

		//url跳转
		public static function urlJump($url)
		{
			echo header('location:'.$url);
			exit;
		}


        //get过滤前$prov_id、$sc_id必须要先加密

        public static function filter($char='',$provRange='',$scRange='',$itemRange='')
        {
		    $url='http://'.$_SERVER['HTTP_HOST'].WEB_NAME;

		    if(!isset($_GET)||empty($_GET))
		    {
			    self::urlJump($url);
		    }

             if (!get_magic_quotes_gpc())
             {
               $_GET = self::stripslashes_array($_GET);
               // var_dump($_GET);
               // exit;
             }

			//Tool::filter($char)，如果$_GET[$char]存在，则验证，如果$char=='sc_id'进行sc_id验证，
			//如果等于item_id，进行item_id验证。如果$_GET[$char]不存在，或者验证不通过，跳到主页或者返回false


           //$flag为标志位，验证通过，则为true,不通过，
           $flag=true;

           if(isset($_GET[$char]))
           {

           		  if($char=='prov_id')
	           	  {
	           	  	   $flag=false;

	           	  	   $str=base64_decode(trim($_GET['prov_id']));

			           $arr=explode('_',$str);
			 	       if(count($arr)==2 && $_SESSION['TOKEN']==$arr['0'])
			 	       {
	                        $prov_id=trim($arr['1']);
	                        if(Validate::isUnsignedInt($prov_id))
	                        {
                               if(in_array($prov_id,unserialize($provRange)))
	                        	{

	                                $_GET['prov_id']=$prov_id;
	                                $flag=true;
	                        	}
	                        }
	         		   }
	           	  }


	           	  if($char=='sc_id')
	           	  {
	           	  	   $flag=false;

	           	  	   $str=base64_decode(trim($_GET['sc_id']));

			           $arr=explode('_',$str);
			 	       if(count($arr)==2 && $_SESSION['TOKEN']==$arr['0'])
			 	       {
	                        $sc_id=trim($arr['1']);
	                        if(Validate::isUnsignedInt($sc_id))
	                        {
                               if(in_array($sc_id,unserialize($scRange)))
	                        	{
	                                $_GET['sc_id']=$sc_id;
	                                $flag=true;
	                        	}
	                        }
	         		   }
	           	  }


	           	  if($char=='item_id')
	           	  {
           	  	      $flag=false;
                      $item_id=trim($_GET['item_id']);

                      if(in_array($item_id,unserialize($itemRange)))
                	  {
                   		$_GET['item_id']=$item_id;
                   		$flag=true;
                	  }
	           	  }

                  if($flag){
                  	return $_GET[$char];
                  }

           	}


            if(!isset($_GET[$char])||$flag===false)
            {
           	  // exit('要过滤的字符'.$char.'不存在或不符合条件');
			    self::urlJump($url);
            }
        }



        static function stripslashes_array(&$array)
        {
           while(list($key,$var) = each($array))
           {
               if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key"))
               {
	                  if (is_string($var))
	                  {
	                     $array[$key] =self::lib_replace_end_tag(stripslashes($var));
	                  }
	                  if (is_array($var))
	                  {
	                      $array[$key] =stripslashes_array($var);
	                  }
                }
           }
          return $array;
        }
        //--------------------------

		// 替换HTML尾标签,为过滤服务
		//--------------------------
		static function lib_replace_end_tag($str)
		{
			if (empty($str)) return false;
			$str = htmlspecialchars($str);
			$str = str_replace( '/', "", $str);
			$str = str_replace("\\", "", $str);
			$str = str_replace(">", "", $str);
			$str = str_replace("<", "", $str);
			$str = str_replace("<SCRIPT>", "", $str);
			$str = str_replace("</SCRIPT>", "", $str);
			$str = str_replace("<script>", "", $str);
			$str = str_replace("</script>", "", $str);
			$str=str_replace("select","",$str);
			$str=str_replace("join","",$str);
			$str=str_replace("union","",$str);
			$str=str_replace("where","",$str);
			$str=str_replace("insert","",$str);
			$str=str_replace("delete","",$str);
			$str=str_replace("update","",$str);
			$str=str_replace("like","",$str);
			$str=str_replace("drop","",$str);
			$str=str_replace("create","",$str);
			$str=str_replace("modify","",$str);
			$str=str_replace("rename","",$str);
			$str=str_replace("alter","",$str);
			$str=str_replace("case","",$str);
			$str=str_replace("&","",$str);
			$str=str_replace(">","",$str);
			$str=str_replace("<","",$str);
			$str=str_replace(" ",chr(32),$str);
			$str=str_replace(" ",chr(9),$str);
			$str=str_replace("    ",chr(9),$str);
			$str=str_replace("&",chr(34),$str);
			$str=str_replace("'",chr(39),$str);
			$str=str_replace("<br />",chr(13),$str);
			$str=str_replace("''","'",$str);
			$str=str_replace("css","'",$str);
			$str=str_replace("CSS","'",$str);
			$str=str_replace("!"," ",$str);
			$str=str_replace("~"," ",$str);
			$str=str_replace("%20"," ",$str);



			//echo $str."<hr/>";
			//过滤BOM
			// $str= preg_replace( '/^(\xef\xbb\xbf)/', '', $str);
			// $regex = "/\/|\~|\!|\@|\#|\\$|\%|\^|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/i";
			// $str=preg_replace($regex,"",$str);
			return $str;
		}

		// 字符串加密
		static function getEncryptString($str) {
			return sha1($str);
		}


        // 	生成长度为$len的字母和数字的随机数
		static public function GetfourStr($len)
		{
			$chars_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9",     "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",      "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",      "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",      "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",      "S", "T", "U", "V", "W", "X", "Y", "Z",);
		    $charsLen = count($chars_array) - 1;
		 	$outputstr = "";
		 	for ($i=0; $i<$len; $i++)
		    {
		    	$outputstr .= $chars_array[mt_rand(0, $charsLen)];
		    }
		     return $outputstr;
		}

        //	UUID生成唯一序列
		static public function uuid($prefix = '')
		  {
		    $chars = md5(uniqid(mt_rand(strtotime(microtime()),99999999999999999), true));
		    $uuid  = substr($chars,0,8) . '-';
		    $uuid .= substr($chars,8,4) . '-';
		    $uuid .= substr($chars,12,4) . '-';
		    $uuid .= substr($chars,16,4) . '-';
		    $uuid .= substr($chars,20,12);
		    return $prefix . $uuid;
		  }


		// 获取唯一不重复的id为组合的最终自定义id(f130eb1a41c99e68052236a9c002cd5d)
		/*
			用法：
			$table = 'cook_user';
			$this->getCustomId($table);

			$table = $table.$compid.$shopid;
		*/

		static public function getCustomId($table)
		{

			// 获取指定表最新的自增长id号
			//$id = $this->nextid($table);

			// 生成年月日字符串(20160101)
			//$dateString = date("Ymd");

			//$id=md5($table.microtime().mt_rand(100000,999999));

			$id=md5($table.microtime().mt_rand(strtotime(microtime()),99999999999999999).self::GetfourStr(32).self::uuid($table));
			// 返回最终的用户id(201601019)
			return $id;
		}

        static public function getCustomIdNumber($workId=0)
        {
             $idgen = new IdGenerate($workId);
             return $idgen->nextId($workId);
        }

        // MD5
		static public function getMd5($value)
		{
			return md5($value);
		}

        // 截取字符串
		static public function getSubstr($str,$start,$length)
		{
			return substr($str,$start,$length);
		}


        // 远程下载文件并保存到指定路径
		static public function getFile($url,$save_dir='',$filename='',$type=0)
		{
		  if(trim($url)=='')
		  {
		   return false;
		  }
		  if(trim($save_dir)=='')
		  {
		   $save_dir='./';
		  }
		  if(0!==strrpos($save_dir,'/'))
		  {
		   $save_dir.='/';
		  }
		  //创建保存目录
		  if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true))
		  {
		   return false;
		  }
		 //获取远程文件所采用的方法
		 if($type)
		 {
			  $ch=curl_init();
			  $timeout=5;
			  curl_setopt($ch,CURLOPT_URL,$url);
			  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
			  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
			  $content=curl_exec($ch);
			  curl_close($ch);
		 }
		 else
		 {
			  ob_start();
			  readfile($url);
			  $content=ob_get_contents();
			  ob_end_clean();
		}
		 $size=strlen($content);
		 //文件大小
		 $fp2=@fopen($save_dir.$filename,'a');
		 fwrite($fp2,$content);
		 fclose($fp2);
		 unset($content,$url);
		 return array('file_name'=>$filename,'save_path'=>$save_dir.$filename);
    }



        // 导出json数据 写入文件
		public static function exportJsonData($data,$json_file)
		{
			// 把PHP数组转成JSON字符串
			$json_string = json_encode($data);

			// 写入文件
			//file_put_contents('test.json', $json_string);
			file_put_contents($json_file, $json_string);

			return $json_string;
		}


		// 文件下载
		public static function fileDown($filename)
		{
             //文件下载
			//readfile
			$fileinfo = pathinfo($filename);
			header('Content-type: application/x-'.$fileinfo['extension']);
			header('Content-Disposition: attachment; filename='.$fileinfo['basename']);
			header('Content-Length: '.filesize($filename));
			readfile($filename);
			exit();
		}


		// 导入json数据
		public static function importJsonData($json_file)
		{
			// 从文件中读取数据到PHP变量
			//$json_string = file_get_contents('test.json');
			$json_string = file_get_contents($json_file);

			// 把JSON字符串转成PHP数组
			$data = json_decode($json_string, true);
			// 显示出来看看
			//var_dump($data);
			return $data;
		}



        // 根据身份证号获取年龄（周岁）
		public static function getAgeByIdcard($idcard)
		{

		    //过了这年的生日才算多了1周岁
		    if(empty($idcard)) return '';
		    $date=strtotime(substr($idcard,6,8));

		    //获得出生年月日的时间戳
		    $today=strtotime('today');
		    //获得今日的时间戳
		    $diff=floor(($today-$date)/86400/365);
		    //得到两个日期相差的大体年数

		    //strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比
		    $age=strtotime(substr($idcard,6,8).' +'.$diff.'years')>$today?($diff+1):$diff;

		    return $age;
		}


		// 根据身份证号获取生日
		public static function getBirthByIdcard($idcard)
		{
		    if(empty($idcard)) return '';

		    $year = substr($idcard,6,4);
		    $month = substr($idcard,10,2);
		    $day = substr($idcard,12,2);
            $birth = $year.'-'.$month.'-'.$day;
		    return $birth;
		}



}
?>