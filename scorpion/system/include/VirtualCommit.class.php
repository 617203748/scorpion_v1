<?php
	
	class VirtualCommit
	{
		//虚拟提交
		public static function https_request($url)
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$data = curl_exec($curl);
			if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
			curl_close($curl);
		
			return $data;
		}
		//https请求（支持GET和POST）
		public static function https_request_reply($url, $data = null)
		{
				 $curl = curl_init();
				 curl_setopt ($curl,CURLOPT_HEADER,0); 
				 $header = array ();  
					//$header [] = 'Host:www.XXXX.co';  
					///$header [] = 'Connection: keep-alive';  
					//$header [] = 'User-Agent: ozilla/5.0 (X11; Linux i686) AppleWebKit/535.1 (KHTML, like Gecko) Chrome/14.0.835.186 Safari/535.1';  
					//$header [] = 'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';  
					//$header [] = 'Accept-Language: zh-CN,zh;q=0.8';  
					//$header [] = 'Accept-Charset: GBK,utf-8;q=0.7,*;q=0.3';  
					//$header [] = 'Cache-Control:max-age=0';  
					//$header [] = 'Cookie:t_skey=p5gdu1nrke856futitemkld661; t__CkCkey_=29f7d98'; 
					
				$header [] = 'Content-Type:application/x-www-form-urlencoded'; //application/x-www-form-urlencoded 
				//curl_setopt ($curl,CURLOPT_HTTPHEADER,$header); 
				curl_setopt($curl, CURLOPT_URL, $url);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
				if (!empty($data)){
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
				}
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$result = curl_exec($curl);

				if(curl_errno($curl))
				{
					return 'Errno'.curl_error($curl);
				}

				curl_close($curl);
				return $result;
		}

	
		public static function httpCommit($url,$data=null)
		{

			if(!is_null($data))
			{
				
				return self::https_request_reply($url,$data);
			}
			else
			{
				
				return self::https_request($url);
			}
			
		}
	}

?>