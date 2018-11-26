<?php
/**
 	* 功能说明：<生成编码类>
 	* ============================================================================
 	* 版权所有：山西蒲公英电子商务有限公司。
 	* ----------------------------------------------------------------------------
 	* 作者：薛莹
	*/
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class SetCode
	{
		private $proname; 	//省市名称
		private $prosname;	//省市简称
		private $sql; 
		private $proid;	//省市id
		private $uid;	//校区id

	    // 生成校区编码 校区编码 = 该校区所属省市的id(2位) + 新添加校区id（4位）
	    public static function SetUniCode($proname,$prosname,$uniid,$len)
	    {
	    	//根据省市名称，查省市id
		    $sql2="SELECT id FROM pugoing.province WHERE proname LIKE ".'"%'.$proname.'%"';   
		    $sql3="SELECT id FROM pugoing.province WHERE prosname= '$prosname'";
		    //查询校区最大id
		   // $sql4='SELECT id FROM pugoing.university WHERE id=(SELECT max(id) FROM pugoing.university)';

			if (!empty($proname)) 
			{
				$sql=$sql2;
			}

			if (empty($proname)) 
			{
				$sql=$sql3;
			}

			//该校区所在的省市的省市id
			$rs_proid = $pdo->SELECT($sql);

			foreach( $rs_proid as $value) 
			{
				 $proid=$value->id;
			}

			//省市id设置为二位，不够左边自动补零
			$proid= Tool::SetCode($proid,4);	
			//查找最大校区id
			/*$rs_unid = $pdo->select($sql4);
			foreach( $rs_unid as $val) 
			{
				$uid=$val->id;
		    	$uid++;
			}*/

			//id为下一个id设置成4位，12=》0012
		    $uid=Tool::SetCode($uniid,$len);
			//新添加的校区编码=该校区所属省市的id(2位) + 新添加校区id（4位）
		    $unicode=$proid.$uid;  
		    return $unicode;
	    }	    


	   /** 生成商品货号 | 订单号  组成相同  
	    *  商品货号 = 该商品所属校区的id(4位) + 年月(6位) + 新添加商品id（4位）
	    */
	    public static function SetAllCode($uniid,$len1,$id)
	    {	    				
		    $uniid=Tool::setCode($uniid,$len1);
		    //id为获取的下一个id  (id自增)
		    
		    //code = 所属校区的id(4位) + 年月日(8位) + 下一个id（5位）
		    // $code = $uniid.date('Ymd').$id;
		    //code = 所属校区的id(4位) + 年月(6位) + 下一个id（5位）
		    $code = $uniid.date('Ym').$id;
		    return $code;
	    }	    
		


		//总订单id  
        public function getParentOrderId()
        {
           return 'puPar'.date('YmdHis');
        }
    

		

		
		//代金券编号  
		public function getAgencyCode()
        {
           return 'pu'.time();
        }

		//代金券密码 
		public function getAgencyCodePass()
        {
           return substr(md5('pu'.mt_rand(100000,999999)),0,8);
        }

        //简历id
        public function getResumeCode()
        {
           //return substr(md5('PU_resume'.mt_rand(100000,999999)),0,8);
        	return 'PU_resume'.time().mt_rand(1000,9999);	
        }


		 /**
		 *
		 *  手机号异或生成vipid
		 *
         *  异或方式生成编码 
         *  例：$data=18202864615;data不能为string;
         *      $len=11,则$fix=00000000000, 11个零,
         */ 
        public static function setVipid($data)
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
		*   生成地址id
		*
		* @param    int        $length  输出长度 默认为6
		* @param    string     $chars   可选的 ，默认为 0123456789
		* @return   string     字符串
		*/
		public static function setAddid()
		{				
			$length=6;
			$chars = '0123456789';
			$hash = '';
			$max = strlen($chars) - 1;
			for($i = 0; $i < $length; $i++) 
			{
				$hash .= $chars[mt_rand(0, $max)];
			}
			return time().$hash;
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
		
		
		 

		//订单号 --总订单
		public static function getOrderNo()
		{
			return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
		}
		
		
		//订单号 --详情
		public function getOrderCode()
		{
			//年月日  时分秒  四个随机数
			return microtime().mt_rand(1000,9999);
		}

	}
?>