<?php
	/**
 	* 功能说明：<微信服务器回调类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
	*/

	if(!defined('RYPDINC')) exit("Request Error!!!");
	class WeChatCallback
	{
		//微信回复模板类
		private $tranWeiXin;
		private $eventObject;
		private $accessToken;
		public function __construct()
		{    
			
			$this->tranWeiXin=new WeChatTransmit();
			
		}
		//验证
		public function valid()
		{
			
			if($this->checkSignature()){
				//token验证失败时 可能输出之前有其他的输出 用 ob_clean(); 测试
				ob_clean();
				header('content-type:text');
				$echoStr = $_GET["echostr"];
				echo $echoStr;
				exit;
			}
			
		}
		//验证是否接入了网站
		private function checkSignature()
		{
				$signature = $_GET["signature"];
		        $timestamp = $_GET["timestamp"];
		        $nonce = $_GET["nonce"];	
				$token = trim($_GET["wxid"]);//'90f86db121ecb1f41a884980a133a679';//'90f86db121ecb1f41a884980a133a679';
				$tmpArr = array($token, $timestamp, $nonce);
				sort($tmpArr, SORT_STRING);
				$tmpStr = implode($tmpArr);
				$tmpStr = sha1($tmpStr);

				if( $tmpStr == $signature )
				{
					return true;
				}else{
					return false;
				}
			
		}
		
		//用户测试
		public function responseMsgTest($test)
		{
			$postStr=$GLOBALS["HTTP_RAW_POST_DATA"];
			//$postStr = file_get_contents("php://input");

			if (!empty($postStr))
			{
				$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				$fromUsername = $postObj->FromUserName;
				$toUsername = $postObj->ToUserName;
				$keyword = trim($postObj->Content);
				$time = time();
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
			    if($keyword == "?" || $keyword == "？")
				{
					$msgType = "text";
					$contentStr = date("Y-m-d H:i:s",time()).'<a href="http://www.baidu.com">'.$test.'</a>';
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
					echo $resultStr;
				}
			}else{
				echo "空值";
				exit;
			}
		}
		
		//获取微信发送的信息
		private function getHTTP_RAW_POST_DATA()
		{
				$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
				if (!empty($postStr))
				{
					//获取用户的操作的内容
					$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
					return $postObj;
				}
				else
				{
					echo '';
					exit;
				}
		}
		////返回微信发送的信息
		public function getEventObject()
		{
			
			return $this->getHTTP_RAW_POST_DATA();
		}
		
		//获取access_token 外部调用 通过ToUserName(原始ID)从数据库获取appid和appsecret
		public function getAccessToken($appid,$appsecret)
		{

			$weChatAccessToken=new WeChatAccessToken(array('appid'=>$appid,'appsecret'=>$appsecret));
			$weChatArr=$weChatAccessToken->getAccessToken();
			$this->accessToken=$weChatArr['access_token'];
			return $this->accessToken;
		}


		//发送用户自定义信息 
		public function sendMessageWx($openID,$title,$content,$order_url)
		{
			
			//自定义回复
			$url="https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$this->accessToken;
			$txt='{
				"touser":"'.$openID.'",
				"msgtype":"news",
				"news":{
					"articles":[
					 {
						 "title":"'.$title.'",
						 "description":"'.$content.'",
						 "url":"'.$order_url.'" 
					 }
					 ]
					}
				}';
					$content='<a href="http://www.baidu.com">欢迎点餐</a>';
				$txt2='{"touser":"'.$openID.'","msgtype":"text","text":{"content":"'.$content.'"}}';
			  $result=VirtualCommit::httpCommit($url,$txt);
			  return $result;
			  
		}

		
		/* 用法
        // 发送用户自定义信息
		public function sendMessageWx($openid,$content,$appid='wx70f8181b019fa179',$appsecret='626d23d6f30a0caf808652e7d507be2f')
		{
		
			$weChatAccessToken=new WeChatAccessToken(array('appid'=>$appid,'appsecret'=>$appsecret));
			
			$weChatArr=$weChatAccessToken->getAccessToken();
			
			$auth=new WeChatUserInfo();
			$auth->sendMessageWx($weChatArr['access_token'],$openid,$content,"http://testwebapp.bmkook.com/meal");

		}
		*/

        // 获取微信用户信息 -- 外部调用 入库 openid (wxid)
		public function getUserInfo($openid)
		{
			$url='https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$this->accessToken.'&openid='.$openid.'&lang=zh_CN';
			$result=VirtualCommit::httpCommit($url);
			$userInfo=json_decode($result, true);
			return $userInfo;
		}




	    //定义消息类型处理函数,在接收到的消息中根据消息类型，定义不同的消息处理函数
		public function responseMsg()
		{
				//获取用户的操作的内容
				$postObj =$this->getHTTP_RAW_POST_DATA();
				//消息类型
				$RX_TYPE=trim($postObj->MsgType);
				switch($RX_TYPE)
				{
					
					case "event":
						$resultStr=$this->receiveEvent($postObj);
						break;
					case "text":
						$resultStr=$this->receiveText($postObj);
						break;
					case "image":
						$resultStr=$this->receiveImage($postObj);
						break;
					case "location":
						$resultStr=$this->receiveLocation($postObj);
						break;
					case "voice":
						$resultStr=$this->receiveVoice($postObj);
						break;
					case "video":
						$resultStr=$this->receiveVideo($postObj);
						break;
					case "link":
						$resultStr=$this->receiveLink($postObj);
						break;
					case "news":
						$resultStr=$this->receiveNews($postObj);
						break;
					default:
						$resultStr="不知道的消息类型: ".$RX_TYPE;
						$resultStr=$this->receiveText($postObj);
						break;
				}	
			 
			   // echo $resultStr;
  		
		}
	
		//事件消息
		private function receiveEvent($object)
		{
			
			$contentStr="";
			$resultStr="";


			switch($object->Event)
			{
				case "subscribe":
					
					$contentStr="欢迎亲们关注点餐[微笑]";
					$contentStr .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
				
				   // $resultStr=$this->receiveTextEvent($object,$contentStr);
				   	$this->getAccessToken($appid='wx70f8181b019fa179',$appsecret='626d23d6f30a0caf808652e7d507be2f');
					$this->sendMessageWx($this->accessToken,$object->FromUserName,'ddd',$contentStr,'http://www.baidu.com');

					$this->sendMessageWx($this->accessToken,$object->FromUserName,'ddd','aaaaaa','http://www.baidu.com');

					$this->getUserInfo($object->FromUserName);
					//用户信息
					break;
				case "unsubscribe":
					$contentStr="残忍的取消!!!";
				    $resultStr=$this->receiveTextEvent($object,$contentStr);
					break;
				 case "SCAN":
					$contentStr="欢迎回来[微笑]";
					$contentStr .= (!empty($object->EventKey))?("\n来自二维码场景 ".str_replace("qrscene_","",$object->EventKey)):"";
				    //$resultStr=$this->receiveTextEvent($object,$contentStr);
				   
					$this->getAccessToken($appid='wx70f8181b019fa179',$appsecret='626d23d6f30a0caf808652e7d507be2f');
					$this->sendMessageWx($this->accessToken,$object->FromUserName,'ddd',$contentStr,'http://www.baidu.com');

					$this->sendMessageWx($this->accessToken,$object->FromUserName,'ddd','aaaaaa','http://www.baidu.com');

					$this->getUserInfo($object->FromUserName);
					break;
				case "CLICK":
					switch($object->EventKey)
					{
						case "myOrder":
							$resultStr=$this->receiveTextEvent($object,"我的订单");
							break;
						case "myCash":
							$resultStr=$this->receiveTextEvent($object,"我的代金券");
							break;
						case "myIntegral":
							$resultStr=$this->receiveTextEvent($object,"我的积分");
							break;
						case "myTestCode":
							
							$resultStr=$this->receiveOauthTextEvent($object,"");
							break;
					}
					break;
				  case "LOCATION":
					$contentStr = "上传位置：纬度 ".$object->Latitude.";经度 ".$object->Longitude;

					break;
				 case "VIEW":
					$contentStr = "跳转链接 ".$object->EventKey;
					break;
				 case "MASSSENDJOBFINISH":
					$contentStr = "消息ID：".$object->MsgID."，结果：".$object->Status."，粉丝数：".$object->TotalCount."，过滤：".$object->FilterCount."，发送成功：".$object->SentCount."，发送失败：".$object->ErrorCount;
					break;
				default:
					$contentStr="一个新的事件lp: ".$object->Event;
					break;
			 }
			
			return $resultStr;
		}



		//接收文本消息
		private function receiveText($object)
		{
			$keyword = trim($object->Content);
			$result='';
			$content ='';
			
			//多客服人工回复模式
			if (strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗"))
			{
				$result = $this->tranWeiXin->transmitService($object);
				$postObj = simplexml_load_string($result, 'SimpleXMLElement', LIBXML_NOCDATA);
				switch($postObj->MsgType)
				{
					case "transfer_customer_service":
						$result = $this->tranWeiXin->transmitText($object,"手动回复客户:你好有什么能帮助你的吗");
						break;
				}
				
			}
			//自动回复模式
			else{
				$wxcodeid_model=new LglpWxCodeIdModel();
				
				
				if(trim($keyword)=="?" || trim($keyword)=="？" || trim($keyword)=="help" || trim($keyword)=="帮助" || trim($keyword)=="菜单")
				{
					$wxcodeid=$wxcodeid_model->queryAllWxCodeId();
					$content="佰乐购公众平台帮助!\n\n";
					foreach($wxcodeid as $value)
					{
						$content.='回复 "'.$value->name.'"  查看  '.$value->remark."\n";
					}
					
				}
				else
				{	 //读取公众平台内容
					 $wxcontent_model=new LglpWxContentModel(); 
					 $wx_contents=$wxcontent_model->queryByWxContent($keyword);
					 if($wx_contents)
					 {
						 $content = array();
						foreach($wx_contents as $con)
						{
							//$content=Tool::unHtml($con->url);
						  //数据库内容(暂时只是图文)
							$content[] = array("Title"=>$con->title, "Description"=>$con->description, "PicUrl"=>WEB_URL.$con->picurl, "Url" =>Tool::unHtml($con->url));
							
						}
					
					 }
					 
					 else
					 {
						$content=null; //'此编号暂时没有内容,发送 "?" 查看蒲公英公众平台使用帮助!!';
					 }	
									
				}
				
				if(is_array($content))
				{
					if (isset($content[0]['PicUrl']))
					{
						$result = $this->tranWeiXin->transmitNews($object, $content);
					}else if (isset($content['MusicUrl']))
					{
						$result = $this->tranWeiXin->transmitMusic($object, $content);
					}
				}
				else
				{
					if($content==null)
					{
						$result="";
					}
					else
					{
						$result = $this->tranWeiXin->transmitText($object,$content);
					}
				}
			}

			return $result;
		}
		
	   
		
		//返回图文信息
		private function receiveNews($object)
		{
			
			$url = "http://apix.sinaapp.com/weather/?appkey=".$object->ToUserName."&city=".urlencode($keyword); 
			$output = file_get_contents($url);
			$content = json_decode($output, true);
			
			$resultStr =$this->tranWeiXin->transmitNews($object,$content);
			return $resultStr;
		}
		//接收图片消息
		private function receiveImage($object)
		{
			$content = array("MediaId"=>$object->MediaId);
			$result = $this->tranWeiXin->transmitImage($object, $content);
			return $result;
		}

		//接收位置消息
		private function receiveLocation($object)
		{
			$content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
			$result = $this->tranWeiXin->transmitText($object, $content);
			return $result;
		}

		//接收语音消息
		private function receiveVoice($object)
		{
			if (isset($object->Recognition) && !empty($object->Recognition))
			{
				$content = "你刚才说的是：".$object->Recognition;
				$result = $this->tranWeiXin->transmitText($object, $content);
			}else
			{
				$content = array("MediaId"=>$object->MediaId);
				$result = $this->tranWeiXin->transmitVoice($object, $content);
			}
			return $result;
		}

		//接收视频消息
		private function receiveVideo($object)
		{
			$content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
			$result = $this->tranWeiXin->transmitVideo($object, $content);
			return $result;
		}

		//接收链接消息
		private function receiveLink($object)
		{
			$content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
			$result = $this->tranWeiXin->transmitText($object, $content);
			return $result;
		}




		//---------------按钮事件--Click---------------------//
			//返回文本消息 按钮事件
		private function receiveTextEvent($object,$value)
		{	
			$contentStr=$value;
			$resultStr=$this->tranWeiXin->transmitText($object,$contentStr);
			return $resultStr;
		}
		
		//返回文本消息 按钮事件(授权)
		private function receiveOauthTextEvent($object,$value,$url=null)
		{	
			//自定义微信回调的地址 
			$curtom_path='/weixin/test.php'; //哪个页面调用此链接，就配置那个页面,此页面为微信的回调页面
			$contentStr='<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxe26835e01b7e2c8c&redirect_uri=http://www.pugoing.cn/'.$curtom_path.'&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirec">授权获取用户信息</a>';
			
			$resultStr=$this->tranWeiXin->transmitText($object,$contentStr);
			return $resultStr;
		}
		//返回图文信息 按钮事件 //测试为天气预报
		private function receiveNewsEvent($object,$keyword)
		{
			$url = "http://apix.sinaapp.com/weather/?appkey=".$object->ToUserName."&city=".urlencode($keyword); 
			//$url="http://apix.sinaapp.com/apple/?appkey=trialuser&number=358031058974471";
			$output = file_get_contents($url);
			$content = json_decode($output, true);
			$resultStr =$this->tranWeiXin->transmitNews($object,$content);
			return $resultStr;
		}
		
		//---------------按钮事件--Click---------------------//

		//日志
		 public function logger($log_content)
	    {
		  $this->tranWeiXin->logger($log_content);
	   }


	}
?>