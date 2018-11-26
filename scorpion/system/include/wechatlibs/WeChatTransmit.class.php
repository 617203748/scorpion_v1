<?php

	/**
 	* 功能说明：<回复内容类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
	*/

	if(!defined('RYPDINC')) exit("Request Error!!!");

	//回复模型
	class WeChatTransmit
	{
		 
		public function transmitCan()
		{
			$xmlTpl="<xml><ToUserName><![CDATA[toUser]]></ToUserName>
					<FromUserName><![CDATA[FromUser]]></FromUserName>
					<CreateTime>123456789</CreateTime>
					<MsgType><![CDATA[event]]></MsgType>
					<Event><![CDATA[subscribe]]></Event>
					<EventKey><![CDATA[qrscene_123123]]></EventKey>
					<Ticket><![CDATA[TICKET]]></Ticket>
					</xml>";
			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
			return $result;
		}

		//回复文本消息
		public function transmitText($object, $content)
		{
			$xmlTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[text]]></MsgType>
							<Content><![CDATA[%s]]></Content>
						</xml>";
			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
			return $result;
		}
		//回复图片消息
		public function transmitImage($object, $imageArray)
		{
			$itemTpl = "<Image>
							<MediaId><![CDATA[%s]]></MediaId>
						</Image>";
			$item_str = sprintf($itemTpl, $imageArray['MediaId']);
			$xmlTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[image]]></MsgType>
							$item_str
						</xml>";

			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
			return $result;
		}

	 //回复语音消息
		public function transmitVoice($object, $voiceArray)
		{
			$itemTpl = "<Voice>
							<MediaId><![CDATA[%s]]></MediaId>
						</Voice>";

			$item_str = sprintf($itemTpl, $voiceArray['MediaId']);
			$xmlTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[voice]]></MsgType>
							$item_str
						</xml>";
			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
			return $result;
		}

		//回复视频消息
		public function transmitVideo($object, $videoArray)
		{
			$itemTpl = "<Video>
							<MediaId><![CDATA[%s]]></MediaId>
							<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
						</Video>";

			$item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);
			$xmlTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[video]]></MsgType>
							$item_str
						</xml>";

			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
			return $result;
		}

		//回复图文消息
		public function transmitNews($object, $newsArray)
		{
			if(!is_array($newsArray)){
				return;
			}
			$itemTpl = "<item>
								<Title><![CDATA[%s]]></Title>
								<Description><![CDATA[%s]]></Description>
								<PicUrl><![CDATA[%s]]></PicUrl>
								<Url><![CDATA[%s]]></Url>
						</item>";
			$item_str = "";
			foreach ($newsArray as $item)
			{
				$item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
			}
			$xmlTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[news]]></MsgType>
							<ArticleCount>%s</ArticleCount>
							<Articles>
								$item_str
							</Articles>
						</xml>";

			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
			return $result;
		}

		//回复音乐消息
		public function transmitMusic($object, $musicArray)
		{
			$itemTpl = "<Music>
							<Title><![CDATA[%s]]></Title>
							<Description><![CDATA[%s]]></Description>
							<MusicUrl><![CDATA[%s]]></MusicUrl>
							<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
						</Music>";

			$item_str = sprintf($itemTpl,$musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);
			/*
			$item_str="";
			foreach ($musicArray as $item)
			{
				$item_str = sprintf($itemTpl,$item['Title'], $item['Description'], $item['MusicUrl'], $item['HQMusicUrl']);
			}
			*/
			$xmlTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[music]]></MsgType>
							$item_str
						</xml>";

			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
			return $result;
		}

		//回复多客服消息
		public function transmitService($object)
		{

			
			$xmlTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[transfer_customer_service]]></MsgType>			
						</xml>";
			$result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
			return $result;
		}
		 //日志记录
		public function logger($log_content)
		{
			if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
				sae_set_display_errors(false);
				sae_debug($log_content);
				sae_set_display_errors(true);
			}else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
				$max_size = 10000;
				$log_filename = "log1.xml";
				if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
				file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
			}
		}

		/*
			//返回图文列表
			public function transmitNews($object,$newsArray)
			{

				if(!is_array($newsArray))
				{
					return;
				}

				$item_str="";
				$count=0;
				foreach($newsArray as $item)
				{
					if(empty($item['Url']))
					{
						$item['Url']='http://www.pugoing.cn/m';
					}
					if($count==0)
					{
						$item['Title']='蒲公英天气提醒:'.$item['Title'].'小伙伴们注意防暑啊!!';
						
						$item['PicUrl']='http://www.pugoing.cn/weixin/logo.jpg';
					}
					$count++;
					$item_str.=sprintf($this->itemTpl,$item['Title'],$item['Description'],$item['PicUrl'],$item['Url']);
				}
				$this->newsTpl = str_ireplace("^item_tpl",$item_str,$this->newsTpl);
									
				$result=sprintf($this->newsTpl,$object->FromUserName,$object->ToUserName,time(),count($newsArray));
				return $result;
			}
			*/
	}
?>