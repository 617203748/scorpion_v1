<?php

/**
 * 微信二维码生成
 */
class CodeImg
{

    /**
     * 二维码场景值ID，scene_id参数
     * @var int
     */
    private $id;

    /**
     * 保存生成的二维码ticket
     * @var string
     */
    private $ticket;

    /**
     * 公众号的接口凭据
     * @var string
     */
    private $accessToken;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    /**
     * 获取二维码ticket
     * @return string
     */
    public function getTicket()
    {
        if (null != $this->ticket)
            return $this->ticket;

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=' . $this->accessToken;
        $data = [
            'expire_seconds' => 2592000,//30天，临时二维码最长有效时间
            'action_name' => 'QR_SCENE',//临时型二维码
            'action_info' => [
                'scene' => [
                    'scene_id' => $this->id
                ]
            ]
        ];
        $obj = HttpsRequest::post($url, $data);
        $this->ticket = $obj->ticket;
        return $this->ticket;
    }

    /**
     * 根据ticket生成二维码链接
     * @param  string $ticket
     * @return string
     */
    public function getCodeUrl()
    {
        if (null == $this->ticket)
            $this->getTicket();

        return 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . urlencode($this->ticket);
    }
}