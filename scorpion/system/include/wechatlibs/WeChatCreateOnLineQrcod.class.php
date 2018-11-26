<?php
	/**
 	* 功能说明：<微信在线生成二维码类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
	*/
	class WeChatCreateOnLineQrcod
	{
		private $pars;
		private $ticket;
		private $accessToken;
		public function __construct($value)
		{    
			$this->pars=$value;

			$this->accessToken=new WeChatAccessToken($value);
		}
		 //拦截器(__set)
		public function __set($key, $value) 
		{
			$this->$key = $value;
		}
		
		//拦截器(__get)
		public function __get($key)
		{
			return $this->$key;
		}

		private function getAccessToken()
		{
			return $this->accessToken->getAccessToken();
		}

		//生成临时二维码
		// expire_seconds 该二维码有效时间，以秒为单位。 最大不超过604800（即7天）。
		//action_name 二维码类型，QR_SCENE为临时,QR_LIMIT_SCENE为永久,QR_LIMIT_STR_SCENE为永久的字符串参数值
		//action_info  二维码详细信息  scene 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000） 
		//scene_id 场景值ID，临时二维码时为32位非0整型
		public function createTempQrcod($scene_id)
		{
			
			$accessToken=$this->getAccessToken();
			$url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$accessToken['access_token'];
		
			
			$jsonTexId='{"expire_seconds": 604800, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": '.$scene_id.'}}}';
			
			$result=VirtualCommit::httpCommit($url,$jsonTexId);
			$jsonfo=json_decode($result,true);
			return $jsonfo['ticket'];

		}
		//生成永久二维码 1-100000条
		// expire_seconds 该二维码有效时间，以秒为单位。 最大不超过604800（即7天）。
		//action_name 二维码类型，QR_LIMIT_SCENE为永久数字参数值,QR_LIMIT_STR_SCENE为永久的字符串参数值
		//action_info  二维码详细信息  scene 场景值ID，永久二维码时最大值为100000（目前参数只支持1--100000）
		//scene_id 场景值ID，临时二维码时为32位非0整型，永久二维码时最大值为100000（目前参数只支持1--100000）
		//scene_str 场景值ID（字符串形式的ID），字符串类型，长度限制为1到64，仅永久二维码支持此字段
		public function createPermanentQrcod($tableId)
		{	
			//ticket 要入库
			$accessToken=$this->getAccessToken();
			$url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$accessToken['access_token'];
			$scene_str=md5($tableId);//md5($weChatConfig['compid'].$weChatConfig['shopid'].$weChatConfig['tableid'].$weChatConfig['serviceid']);
			/*
			$jsonTexId='{"action_name": "QR_LIMIT_SCENE",
						"action_info": {
							"scene": {
								"scene_id": 1
							}
						}
					}';*/
			
			$jsonTexStr='{"action_name": "QR_LIMIT_STR_SCENE", 
			"action_info": {
				"scene": {
					"scene_str": "'.$scene_str.'"
					}
				}
			}';
			
			$result=VirtualCommit::httpCommit($url,$jsonTexStr);
			$jsonfo=json_decode($result,true);

			return $jsonfo['ticket'];

		}

		// 生成二维码
		public function createQrcod($onlyID,$type='P')
		{
			switch(strtoupper($type))
			{
				case 'P':
					return $this->createPermanentQrcod($onlyID);
					break;
				case 'T':
					return $this->createTempQrcod($onlyID);
					break;
			}
		}


		//显示二维码
		public function showQrcod($ticket) 
		{
			$url='https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=';
			$showQrcod=$url.UrlEncode($ticket);
			return $showQrcod;
		}
	}
?>