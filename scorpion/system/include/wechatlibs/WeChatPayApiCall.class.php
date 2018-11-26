<?php


	/**
 	* 功能说明：<微信支付二次开发类>
 	* ============================================================================
 	* 版权所有：太原锐意鹏达科技有限公司，并保留所有权利。
 	* ----------------------------------------------------------------------------
	*/
	
	if(!defined('RYPDINC')) exit("Request Error!!!");

	class WeChatPayApiCall
	{
		
		
		//微信支付 JsAPi支付
		public function getWxJsAPIParameters($goods_desc,$order_no,$total_price,$openid)
		{	
			
			$jsApi=new JsApi_pub();
			/* 
			//=========步骤1：网页授权获取用户openid============ 在进入 webapp 的时候 已经进行了授权 所以这里不用
			//通过code获得openid
			
			if (!isset($_GET['code']))
			{
				
				//触发微信返回code码
				$url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
				Header("Location: $url"); 
				

				
			}else
			{
				//获取code码，以获取openid
				$code = $_GET['code'];
				$jsApi->setCode($code);
				$openid = $jsApi->getOpenId();

			}
			*/
			//=========步骤2：使用统一支付接口，获取prepay_id============
			//使用统一支付接口
			$total_price=$total_price*100;
			$unifiedOrder = new UnifiedOrder_pub();
			
			//设置统一支付接口参数
			//设置必填参数
			//appid已填,商户无需重复填写
			//mch_id已填,商户无需重复填写
			//noncestr已填,商户无需重复填写
			//spbill_create_ip已填,商户无需重复填写
			//sign已填,商户无需重复填写
			$unifiedOrder->setParameter("openid",$openid);//商品描述
			$unifiedOrder->setParameter("body",$goods_desc);//商品描述

			//自定义订单号，此处仅作举例
			$timeStamp = time();
			$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
			$unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号 
			$unifiedOrder->setParameter("total_fee",$total_price); //总金额 是已'分'为单位的 如: 1 默认为0.01元  
			$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
			$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
			
			$prepay_id = $unifiedOrder->getPrepayId();
			//=========步骤3：使用jsapi调起支付============
			$jsApi->setPrepayId($prepay_id);
			$jsApiParameters = $jsApi->getParameters();
		
			return $jsApiParameters;
		}
		//Native（原生）支付-模式
		public function getWxPayNavtiveParameters($goods_desc,$order_no,$total_price)
		{
			//使用统一支付接口
			$unifiedOrder = new UnifiedOrder_pub();
		
			//设置统一支付接口参数
			//设置必填参数
			//appid已填,商户无需重复填写
			//mch_id已填,商户无需重复填写
			//noncestr已填,商户无需重复填写
			//spbill_create_ip已填,商户无需重复填写
			//sign已填,商户无需重复填写
			$unifiedOrder->setParameter("body",$goods_desc);//商品描述
			//自定义订单号，此处仅作举例
			$timeStamp = time();
			$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
			$unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
			$unifiedOrder->setParameter("total_fee",$total_price);//总金额
			$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
			$unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
			//非必填参数，商户可根据实际情况选填
			//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
			//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
			//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
			//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
			//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
			//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
			//$unifiedOrder->setParameter("openid","XXXX");//用户标识
			//$unifiedOrder->setParameter("product_id","XXXX");//商品ID
				
			//获取统一支付接口结果
			$unifiedOrderResult = $unifiedOrder->getResult();

			
			$code_url="";
			//商户根据实际情况设置相应的处理流程
			if ($unifiedOrderResult["return_code"] == "FAIL") 
			{
				//商户自行增加处理流程
				echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
			}
			elseif($unifiedOrderResult["result_code"] == "FAIL")
			{
				//商户自行增加处理流程
				echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
				echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
			}
			elseif($unifiedOrderResult["code_url"] != NULL)
			{
				//从统一支付接口获取到code_url
				$code_url = $unifiedOrderResult["code_url"];
				
				
				//商户自行增加处理流程
				//......
			}

			return $code_url;
		}
	}
?>

