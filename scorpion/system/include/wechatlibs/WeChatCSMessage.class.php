<?php
	/**
 	* 功能说明：<微信自定义发送消息类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
	*/

	if(!defined('RYPDINC')) exit("Request Error!!!");
	class WeChatCSMessage
	{
        // 提交
		private static function commit($messages)
		{
			 $url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$messages['access_token'];
			 $result=VirtualCommit::httpCommit($url,$messages['sendmessage']);

			 return $result;
		}

        // 发送文本消息
		public static function sendMessageText($messages)
		{	 

			/*
			$txtArr['touser']=$messages['openid'];//;
			$txtArr['msgtype']='text';
			$txtArr['text']['content']=urlencode($messages['content']);
			urldecode(json_encode($txtArr));*/

			 $messages['sendmessage']= '{"touser":"'.$messages['openid'].'", "msgtype":"text", "text":{ "content":"'.$messages['content'].'"}}'; //
			 self::commit($messages);
		}


        // 发送图片消息
		public static function sendMessageImage($messages)
		{
			 $messageType='{"touser":'.$messages['openid'].'", "msgtype":"image", "image":{ "content":"'.$messages['content'].'"}}';
			 $messages['sendmessage']= $messageType;
			self::commit($messages);
		}


        // 发送图文消息
		public static function sendMessageNews($messages)
		{

             //Log::write('$messages== '.json_encode($messages));
			 /*"picurl":"http://images.bmkook.com/upload/shops/photo/20160714/20160714154850796.png"
			  "picurl":"'.$messages['picurl'].'"*/


			  /*	
			 $messageType='{
							"touser":"'.$messages['openid'].'",
							"msgtype":"news",
							"news":{
								"articles":[

								 {
									 "title":"'.$messages['title'].'",
									 "description":"'.$messages['content'].'",
									 "url":"'.$messages['url'].'",
									 "picurl":"http://images.bmkook.com/upload/'.$messages['picurl'].'"
								 }
								
								 
								 ]
								}
							}';*/

				 $messageType='{
					"touser":"'.$messages['openid'].'",
					"msgtype":"news",
					"news":{
						"articles":[

						';

							
					
				  foreach($messages['msg'] as $key=>$value)
				  {
				  		$messageItem='
				  		{
							 "title":"'.$messages['msg'][$key]['title'].'",
							 "description":"'.$messages['msg'][$key]['content'].'",
							 "url":"'.$messages['msg'][$key]['url'].'",
							 "picurl":"'.$messages['msg'][$key]['picurl'].'"
						 }';

						
				  		$messageType.=$messageItem.',';

				  }

 			
				 $messageType.='	

								 ]
								}
							}';
						
						  
					
						
			$messages['sendmessage']= $messageType;
			 self::commit($messages);
		}





        
		public static function sendMessage($messages=array(),$type='text')
		{
			if(count($messages)>0)
			{
				
				switch(strtoupper($type))
				{
					case 'TEXT' :
						// 文本消息
                        self::sendMessageText($messages);
						break;
					case 'IMAGE':
						// 图片消息
						break;
					case 'VOICE':
						// 语音消息
						break;
					case 'VIDEO':
						// 视频消息
						break;
					case 'MUSIC':
						// 音乐消息
						break;
					case 'NEWS':
						// 图文消息(新闻消息)
					    self::sendMessageNews($messages);
						break;
					
				}
			}
		}
	}
?>