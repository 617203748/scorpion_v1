<?php

	


class WeChatCreateId
{
	//获取微信Token 
	public static function createTokenCode($strValue)
	{
		//Tool::getMd5($this->compid.$shopid.$this->model->app_id.$this->model->original_id);
		//
		return self::md5($strValue);
	}
	//获取微支付的 支付key
	public static function createPayKeyCode($strValue)
	{
		//Tool::getMd5($this->compid.$shopid.$this->model->app_id.$this->model->original_id.$this->model->mchid);
		return self::md5($strValue);
	}

	private static function md5($value)
	{
		return Tool::getMd5($value);
	}
}
