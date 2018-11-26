<?php
	
	class WeChatAccessToken
	{
		private $pars;
		private $myMem;
		public function __construct($value)
		{    
			$this->pars=$value;
			$this->myMem=MemcachedCache::getInstance();


		}
		
 	  //给key设置Mem
		private function setMem($key,$value)
		{
			return $this->myMem->set($key,$value);
		}

        
		// 获取key的Mem
		private function getMem($key)
		{
			return $this->myMem->get($key);
		}

		//本地获取 微信token（如果不成功或者超时，就去远程获取）
		public function getAccessToken()
		{
			//获取本地缓存的access_token信息
			//$localToken = $this->rAccessToken();
			
			//获取的微信access_token 信息
			//$tokenArr = json_decode($localToken,true);
			/*
			//判断本地的weixin_token是否存在
			if(!is_array($tokenArr) || !isset($tokenArr['get_token_time']) )
			{
				
				//去微信获取，然后保存
				$tokenArr = $this->getRemoteAccessToken();
				//$this->cudAccessToken($tokenArr);
				$this->writeFileName($tokenArr);
			}
			else
			{
				//当前时间
				$nowTime=time();
				//判断 当前时间 减去 本地获取微信token的时间 大于7000秒 ,就要重新获取
				//if($nowTime - $tokenArr['get_token_time'] >7000)
				if($nowTime > $tokenArr['get_token_time'])
				{
					//$this->deleteAccessTokenFile();
					
					$tokenArr = $this->getRemoteAccessToken();
					//$this->cudAccessToken($tokenArr);
					$this->writeFileName($tokenArr);
					
				}
			}*/

			$localToken = $this->rAccessToken();


			$tokenArr;
			if($localToken)
			{
				$tokenArr = json_decode($localToken,true);

				
				//当前时间
				$nowTime=time();

				//判断 当前时间 减去 本地获取微信token的时间 大于7000秒 ,就要重新获取
				//if($nowTime - $tokenArr['get_token_time'] >7000)
				if($nowTime > $tokenArr['get_token_time'])
				{
					//$this->deleteAccessTokenFile();
					$tokenArr = $this->getRemoteAccessToken();
					//$this->cudAccessToken($tokenArr);
					$this->writeFileName($tokenArr);					
				}
			}
			else
			{
				//去微信获取，然后保存
				$tokenArr = $this->getRemoteAccessToken();
				//$this->cudAccessToken($tokenArr);
				$this->writeFileName($tokenArr);

				
			 
			}
			//Log::write('关注 localToken  ==== 6   '.json_encode($tokenArr));
			return $tokenArr;
		}

	

	   public function writeFileName($content) 
	   {
	  
            $key=$this->getFileName();
			$this->setMem($key,json_encode($content));
			/*
		    $filename=$this->getFileName();
			$fp = fopen($filename, "w+");
			fwrite($fp, json_encode($content));
			fclose($fp);*/
	   }

	   private function rAccessToken()
		{	
			/*
			$filename=$this->getFileName();
			if(file_exists($filename))
			{
				$result=file_get_contents($filename);

				return $result;
			}
			
			return false;*/
			
			$key=$this->getFileName();
            return $this->getMem($key);

		}
		

		public function getFileName($cusName='')
		{	
			/*
			$path=WECHAT_TEMP;
			//$fileName=$this->pars['appid'].$this->pars['appsecret'].$cusName;
			$fileName=$this->pars['appid'].$cusName;
			if(!is_dir($path))
			{
				mkdir($path);
				chmod($path,0777);
			}
			
			return $path.md5($fileName).'_access_token.txt';*/

			return $this->pars['appid'].$cusName;
		}
		
		private function deleteAccessTokenFile()
		{
			$filename=$this->getFileName();
			if(file_exists($filename))
			{
				@unlink($filename);
			}
			
		}

		private function cudAccessToken($value)
		{
			$filename=$this->getFileName();
			//保存到本地
		    file_put_contents($filename,json_encode($value));
		}

		

		private function getRemoteAccessToken()
		{

				//获取token
				//$appid = "wxe26835e01b7e2c8c";
				//$appsecret = "4345b0900e0f0bd22d2b505c2f71be73";
				$access_token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->pars['appid']."&secret=".$this->pars['appsecret'];
				$result=VirtualCommit::httpCommit($access_token_url);
				$tokenJsoninfo=json_decode($result, true);
				//$tokenJsoninfo['get_token_time']=time();
				$tokenJsoninfo['get_token_time']=time() + 7000;
				//$access_token = $tokenJsoninfo["access_token"];
				return $tokenJsoninfo;
		}


	
	}
?>