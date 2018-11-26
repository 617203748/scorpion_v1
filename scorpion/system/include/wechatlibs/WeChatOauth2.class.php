<?php
	/**
 	* 功能说明：<微信网页授权类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
	*/
class WeChatUserInfo
{
	
	//private $appid='wx6f3e48ec49824ee8';
	//private $appsecret='a1ea02a26394aeff7cf76b79fa6efb51';
	

	// 网页授权获取用户信息 服务号
	public function getServiceUserInfo($code,$appid,$appsecret)
	{
		//$appid = "wx70f8181b019fa179";
	    //$appsecret = "626d23d6f30a0caf808652e7d507be2f";
		 //第二步 通过code换取网页授权access_token 此access_token与基础支持的access_token不同
		$access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
		$access_token_json =$this->https_request($access_token_url);
	
		$access_token_array = json_decode($access_token_json, true);
		$access_token = $access_token_array['access_token'];
		$openid = $access_token_array['openid'];


		//检验授权凭证（access_token）是否有效 access_token有可能过期
		//由于access_token拥有较短的有效期，当access_token超时后，
		//可以使用refresh_token进行刷新，refresh_token拥有较长的有效期（7天、30天、60天、90天），当refresh_token失效的后，需要用户重新授权。
		$yanzheng_url="https://api.weixin.qq.com/sns/auth?access_token=".$access_token."&openid=".$openid;
		$yanzheng_json =$this->https_request($yanzheng_url);
		$yanzheng_array = json_decode($yanzheng_json, true);
		 if($yanzheng_array['errcode']>0)//大于0是有错误可以打印到手机微信客户端查看
		 {
			// print_r($yanzheng_array); exit; //测试用能打印到手机微信客户端
			 //echo '有事';

			$refresh_toke=$access_token_array['refresh_token']; //refresh_token拥有较长的有效期（7天、30天、60天、90天）过期时间较长
			//第三步：刷新access_token（如果需要）
			$refresh_token_url="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$appid."&grant_type=refresh_token&refresh_token=".$refresh_toke;
			$refresh_json = $this->https_request($refresh_token_url);
			$refresh_array = json_decode($refresh_json, true);

			$access_token = $refresh_array['access_token'];
			$openid = $refresh_array['openid'];
		 }
		

		//第四步 拉取用户信息(需scope为 snsapi_userinfo)
		$userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
		$userinfo_json =$this->https_request($userinfo_url);
		$userinfo_array = json_decode($userinfo_json, true);
		
		
		return $userinfo_array;

		
	}
	
  
	
	
	//虚拟提交
	public function https_request($url)
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
	public function https_request_reply($url, $data = null)
	{
			$curl = curl_init();
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
}

?>