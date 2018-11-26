<?php

/**
 * Curl
 */
class HttpsRequest
{
    /**
     * 发送https的get请求
     * @param  string $url
     * @return obj
     */
    public static function get($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output);
    }

    /**
     * 发送https的POST请求
     * @param  string $url
     * @param  mix $data json | array
     * @return obj
     */
    public static function post($url, $data = null)
    {
        if (is_array($data))
            $data = json_encode($data);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (null != $data) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output);
    }
}