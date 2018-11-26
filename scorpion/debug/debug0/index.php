<?php

define('APPID', 'wx78716ab2a81efbdd');
define('APPSECRET', 'd5400d88e581bc7d7537b013e7bb496c');
define('TOKEN', 'sxbdjw');

//创建菜单
$menu = <<<JSON
{
	"button":[
		{
			"type":"view",
			"name":"北斗微信",
			"url":"https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx78716ab2a81efbdd&redirect_uri=http%3a%2f%2fapi.eme.sxbdjw.cn%2fapp_wx%2findex.php&response_type=code&scope=snsapi_base#wechat_redirect"
		}
	]
}
JSON;

//https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx78716ab2a81efbdd&redirect_uri=http%3a%2f%2fapi.eme.sxbdjw.cn%2fapp_wx%2findex.php&response_type=code&scope=snsapi_base#wechat_redirect

//执行创建菜单
$wechat = new WeChat(APPID, APPSECRET, TOKEN);
$result = $wechat->_doCreateMenu($menu);

echo 'result:' . $result;


class WeChat
{
    private $_appid;
    private $_appsecret;
    private $_token;//公众平台请求开发者时需要的标记

    private $_msg_template = array(
        'text' => '<xml>
						<ToUserName>
							<![CDATA[%s]]>
						</ToUserName>
						<FromUserName>
							<![CDATA[%s]]>
						</FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType>
							<![CDATA[text]]>
						</MsgType>
						<Content>
							<![CDATA[%s]]>
						</Content>
					</xml>',
        'image' => '<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[image]]></MsgType>
					<Image>
					<MediaId><![CDATA[%s]]></MediaId>
					</Image>
					</xml>',

    );


    const QRCODE_TYPE_TEMP = 1;
    const QRCODE_TYPE_STR = 2;
    const QRCODE_TYPE_LIMIT = 3;
    const QRCODE_TYPE_LIMIT_STR = 4;

    public function __construct($id, $secret, $token)
    {
        $this->_appid = $id;
        $this->_appsecret = $secret;
        $this->_token = $token;
    }

    /**
     *    核心方法，调用回复信息
     *
     **/
    public function responseMsg()
    {
        //获取请求时post的xml字符串
        //因为接收的post数据不是key/value型，所以不能使用$_GET[]数组
        //使用$GLOBALS['HTTP_RAW_POST_DATA'];http未加工的post数据
        $xml_str = $GLOBALS['HTTP_RAW_POST_DATA'];

        //如果没有post数据，则响应空字符串表示结束
        if (empty($xml_str)) {
            die('');
            // exit;
        }

        //解析该xml字符串，利用simpleXML
        libxml_disable_entity_loader(true);//禁止xml实体解析，防止xml注入
        //从字符串获取simplexml对象
        $request_xml = simplexml_load_string($xml_str, 'SimpleXMLElement', LIBXML_NOCDATA);

        //判断该消息的类型通过元素：MsgType-->是否关注
        switch ($request_xml->MsgType) {
            case 'event'://事件类型
                //判断具体的事件类型（关注，取消，点击）
                $event = $request_xml->Event;
                if ('subscribe' == $event) {
                    $this->_doSubscribe($request_xml);
                } else if ('click' == $event) {
                    $this->_doClickEvent($request_xml);
                }
                break;
            case 'text'://文本消息
                $this->_doText($request_xml);
                break;
            case 'image'://图片消息
                $this->_doImage($request_xml);
                break;
            case 'voice':
                $this->_doVoice($request_xml);
                break;
            case 'video':
                $this->_dovideo($request_xml);
                break;
            case 'shortvideo':
                $this->_doShortvideo($request_xml);
                break;
            case 'location':
                $this->_doLocation($request_xml);
                break;
            case 'link':
                $this->_doLink();
                break;

            default:
                break;

        }
    }


    /**
     *    创建菜单
     **/
    public function _doCreateMenu($menu)
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=" . $this->getAccessToken();
        $data = $menu;
        $result = $this->_requestPost($url, $data);

        return $result;
    }

    //用户发送文本
    public function _doText($request_xml)
    {
        //获取接收的文本内容
        $content = $request_xml->Content;
        //对文本内容判断回复消息
        if ('?' == $content || '??' == $content || '???' == $content) {
            $response_content = '请回复1、2、3';
            $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $response_content);
        } else if ('1' == $content) {
            $response_content = '测试1';
            $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $response_content);
        } else if ('2' == $content) {
            $response_content = '测试2';
            $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $response_content);
        } else if ('3' == $content) {
            $response_content = '测试3';
            $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $response_content);
        } else {
            $response_content = '请正确输入';
            $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $response_content);
        }
    }

    //用户发送图片
    private function _doImage($request_xml)
    {
        $content = '你所上传的图片的Media_ID' . $request_xml->MediaId . '图片链接为:' . $request_xml->PicUrl;
        // $this->_msgImage($request_xml->FromUserName,$request_xml->ToUserName,$content);
        $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $content);
    }

    //用户发送语音
    private function _doVoice($request_xml)
    {
        $content = '你发送的语音Media_ID:' . $request_xml->MediaId;
        $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $content);
    }

    //用户发送小视频
    private function _doShortvideo($request_xml)
    {
        $content = '你发送的小视频media_id是:' . $request_xml->MediaId;
        $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $content);
    }

    //用户发送地图信息
    private function _doLocation($request_xml)
    {
        /*$content = '你的坐标为：经度:'.$request_xml->Location_Y.'纬度:'.$request_xml->Location_X.'\n'.'你所在的位置为:'.$request_xml->Label;
        $this->_msgText($request_xml->FromUserName,$request_xml->ToUserName,$content);*/

        // $url = 'http://api.map.baidu.com/place/v2/search?query=%s&location=%s&radius=%s&output=%s&ak=%s';

        $query = '餐饮';
        $filter = 'industry_type:cater|sort_name:price|sort_name:distance|sort_rule:0';
        $scope = 2;
        $location = $request_xml->Location_X . ',' . $request_xml->Location_Y;
        $radius = 2000;
        $output = 'json';
        $ak = 'nduymETBIPIepn5ppBYk94zMmCxO53wh';
        // $url = sprintf($url,urlencode($query),$location,$radius,$output,$ak);
        $url = 'http://api.map.baidu.com/place/v2/search?query=' . urlencode($query) . '&scope=' . $scope . '&location=' . $location . '&radius=' . $radius . '&output=' . $output . '&filter=' . $filter . '&ak=' . $ak;

        $result = $this->_requestGet($url, false);
        $result_obj = json_decode($result);

        $result_list = array();
        foreach ($result_obj->results as $result_val) {
            $r['name'] = $result_val->name;
            $r['address'] = $result_val->address;
            $r['telephone'] = $result_val->telephone;
            $r['price'] = $result_val->price;
            $result_list[] = implode('-', $r);
        }
        $result_str = implode("\n", $result_list);
        $this->_msgText($request_xml->FromUserName, $request_xml->ToUserName, $result_str);
    }


    private function _msgText($toUserName, $fromUserName, $content)
    {
        $response = sprintf($this->_msg_template['text'], $toUserName, $fromUserName, time(), $content);
        die($response);
    }

    private function _msgImage($toUserName, $fromUserName, $mediaId)
    {
        $response = sprintf($this->_msg_template['image'], $toUserName, $fromUserName, time(), $mediaId);
        die($response);
    }


    /**
     *    用户第一次验证url合法性
     **/

    public function firstValid()
    {
        //检验签名的合法性
        if ($this->_checkSignature()) {
            //签名合法，告知微信公众平台服务器
            header('content-type:text');
            echo $_GET['echostr'];
        }
    }

    /**
     * 验证签名
     * @return bool [description]
     **/

    private function _checkSignature()
    {
        //获取微信公众平台请求的验证数据
        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];

        //将时间戳、随机字符串、token按照字母顺序排序并连接
        $tmp_arr = array($this->_token, $timestamp, $nonce);
        sort($tmp_arr, SORT_STRING);//按字典顺序排列
        //将$tmp_arr数组转为字符串
        $tmp_str = implode($tmp_arr);
        //加密(sha1签名)
        $tmp_str = sha1($tmp_str);
        //验证加密后是否合法
        if ($signature == $tmp_str) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *获取access_token
     * @param string $token_file用来存储token的临时文件
     **/

    public function getAccessToken($token_file = 'access_token')
    {
        // public function getAccessToken(){
        //考虑是否过期问题，将获取的access_token存储到某个文件中
        $life_time = 7200;
        if (file_exists($token_file) && time() - filemtime($token_file) < $life_time) { //filemtime 获取文件最后一次的修改时间
            //存在有效的access_token
            return file_get_contents($token_file);
        }


        //目标url
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->_appid}&secret={$this->_appsecret}";
        //向该url，发送get请求
        $result = $this->_requestGet($url);
        if (!$result) {
            return false;
        }
        //存在返回响应结果
        $result_obj = json_decode($result);
        // file_put_contents($token_file,$result_obj->access_token);
        // echo $result_obj;
        // exit;
        return $result_obj->access_token;
    }


    /**
     * 1、获取(post请求)QRCodeTicket
     * 2、通过QRCode ticket 获取二维码图片(QRCodeImg)
     * @param $content 二维码内容
     * @param actionNameType 判断二维码为哪种类型(临时、永久)
     * @param expire_seconds 二维码的有效时间，最大不超过2592000(即30天)
     * @return string ticket
     **/
    private function _getQRCodeTicket($content, $actionNameType = 1, $expireSecond = 2592000)
    {    //先获取QRCode Ticket在用ticket获取QRCodeImg
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=$access_token";

        $type_list = array(
            self::QRCODE_TYPE_TEMP => 'QR_SCENE',
            self::QRCODE_TYPE_STR => 'QR_STR_SCENE',
            self::QRCODE_TYPE_LIMIT => 'QR_LIMIT_SCENE',
            self::QRCODE_TYPE_LIMIT_STR => 'QR_LIMIT_STR_SCENE'
        );
        $actionName = $type_list[$actionNameType];
        //判断二维码类型(1、判断二维码为临时还是永久;2、判断二维码是整形参数还是字符串参数)
        switch ($actionNameType) {
            case self::QRCODE_TYPE_TEMP:
            case self::QRCODE_TYPE_STR:
                $data_arr['expire_seconds'] = $expireSecond;
                $data_arr['action_name'] = $actionName;
                $data_arr['action_info']['scene']['scene_id'] = $content;
                break;
            case self::QRCODE_TYPE_LIMIT:
            case self::QRCODE_TYPE_LIMIT_STR:
                $data_arr['action_name'] = $actionName;
                $data_arr['action_info']['scene']['scene_id'] = $content;
                break;
        }

        $data = json_encode($data_arr);
        // $data = "{'action_name': '$actionName', 'action_info': {'scene': {'scene_id': $content}}}";//scene为场景即二维码内容
        $result = $this->_requestPost($url, $data);//通过post去传值,返回result

        if (!$result) {
            return false;
        }
        //结果不为空时,处理响应数据(数据格式为json)
        $result_obj = json_decode($result);
        return $result_obj->ticket;
    }


    /**
     *    getQRCode description
     * @param int /string $content qrcode的内容标识
     * @param              $file 存储为文件的地址，如果为null表示直接输出
     * @param int $type 二维码的类型
     * @param int $expire 如果是临时二维码，表示其有效期
     * @param
     **/

    public function getQRCode($content, $file = NULL, $actionNameType = 1, $expireSecond = 2592000)
    {
        //获取ticket
        $ticket = $this->_getQRCodeTicket($content, $actionNameType = 1, $expireSecond = 2592000);
        //利用ticket获取二维码
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
        //发送get请求获取二维码
        $result = $this->_requestGet($url);//此时的result就是图像内容
        //输入图片
        // if($file){
        // file_put_contents($file,$result);
        // }else{
        header('Content-Type: image/jpeg');
        echo $result;
        // }

    }

    private function _requestPost($url, $data, $ssl = true)
    {
        //curl初始化curl资源
        $curl = curl_init();
        //设置curl get设置

        //设定url
        curl_setopt($curl, CURLOPT_URL, $url);

        //user_agent请求代理信息
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.36';
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);

        //referer头,请求来源
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        //SSL相关
        if ($ssl) {
            //对于简单验证来说终止掉curl从服务器端进行的验证
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //检查服务器ssl证书中的是否存在一个公用名(common name)
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        }

        //接收响应(http  请求后，处理响应头)
        curl_setopt($curl, CURLOPT_POST, true);//是否为POST请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//处理请求数据

        //处理响应结果
        curl_setopt($curl, CURLOPT_HEADER, false); //是否处理响应头
        //设定curl_returntransfer,设定后curl_exec()(发出请求)返回为文件流,不是输出数据
        //即curl_exec()是否返回响应结果
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //发出请求
        $response = curl_exec($curl);
        if (false === $response) {
            echo '<br>', curl_error($curl), '<br>';
            return false;
        }

        return $response;
    }


    /**
     *发送get请求的方法
     * @param string $url URl
     * @param bool $ssl 是否为https协议
     * @return string 响应主体Content
     **/
    private function _requestGet($url, $ssl = true)
    {
        //curl初始化curl资源
        $curl = curl_init();
        //设置curl get设置

        //设定url
        curl_setopt($curl, CURLOPT_URL, $url);

        //user_agent请求代理信息
        $user_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.155 Safari/537.36';
        curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);

        //referer头,请求来源
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        //SSL相关
        if ($ssl) {
            //对于简单验证来说终止掉curl从服务器端进行的验证
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            //检查服务器ssl证书中的是否存在一个公用名(common name)
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        }

        //接收响应(http get 请求后，处理响应头)
        //是否处理响应头
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设定curl_returntransfer,设定后curl_exec()(发出请求)返回为文件流,不是输出数据
        //即curl_exec()是否返回响应结果
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        //发出请求
        $response = curl_exec($curl);
        if (false === $response) {
            echo '<br>', curl_error($curl), '<br>';
            return false;
        }

        return $response;
    }

    /**
     *    通过access_token获取jsapi_ticket的方法
     * @return JSON jsapi_ticket
     **/
    public function getJsapi_ticket()
    {
        $access_token = $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=" . $access_token . "&type=jsapi";
        $result = $this->_requestGet($url);

        //获取jsapi_ticket生成JS-SDK权限验证的签名
        //对noncestr（随机字符串）, 有效的jsapi_ticket, timestamp（时间戳）, url进行字典排序
        $nonceStr = 'guoyan';

        $jsapiJson = json_decode($result);
        $jsapi_ticket = $jsapiJson->ticket;

        $timestamp = time();

        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        // $jsapi_url = 'http://2.testwechat.applinzi.com/web.php';
        //将各个参数转为数组,将数组字典排序,将排序后的数组转为字符串
        $tmp_arr = array(
            'noncestr' => $noncestr,
            'jsapi_ticket' => $jsapi_ticket,
            'timestamp' => $timestamp,
            'url' => $jsapi_url);
        sort($tmp_arr, SORT_STRING);
        // $tmp_str = "jsapi_ticket=$jsapi_ticket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        // $tmp_str = implode($tmp_arr,'&');
        $signature = sha1($tmp_str);

        $result_array = array(
            'time' => $timestamp,
            'signature' => $signature,
        );

        return $result_array;
    }
}