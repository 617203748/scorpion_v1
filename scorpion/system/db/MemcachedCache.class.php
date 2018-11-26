<?php
/**
 * Memcache 操作类
 *
 * 在config文件中 添加 
     相应配置(可扩展为多memcache server)
    define('MEMCACHE_HOST', '10.35.52.33');
	define('MEMCACHE_PORT', 11211);
	define('MEMCACHE_EXPIRATION', 0);
	define('MEMCACHE_PREFIX', 'licai');
	define('MEMCACHE_COMPRESSION', FALSE);
    demo:
 		$cacheObj = new MemcachedCache(); 		
		$cacheObj -> set('keyName','this is value');
 		$cacheObj -> get('keyName');
	    exit;
 * @access  public
 * @return  object
 * @date    2012-07-02
 */
if(!defined('RYPDINC')) exit("Request Error!!!");
//php Memcached 扩展
defined('MEM_D') OR define('MEM_D', 'Memcached');
//php Memcache 扩展
defined('MEM_C') OR define('MEM_C', 'Memcache');

class MemcachedCache{


	private $local_cache = array();
	private $m;
	private $client_type;
	protected $errors = array();
	private static  $instance;
	public function __construct()
	{	
		$this->client_type = extension_loaded(MEM_D) ? MEM_D : (extension_loaded(MEM_C) ? MEM_C : false); 
		if($this->client_type)
		{
			// 判断引入类型
			switch($this->client_type)
			{
				case MEM_D:
					$this->m = new Memcached();
					break;
				case MEM_C:
				
					$this->m = new Memcache();
					// if (auto_compress_tresh){
						// $this->setcompressthreshold(auto_compress_tresh, auto_compress_savings);
					// }
					break;
			}
			$this->auto_connect();	
		}
		else
		{
			echo 'php 中没有 Memcache 或 Memcached 扩展  !!';
			exit;
		}
	}
	
	//单例
	public static function getInstance()  
    {  
		
        if(!(self::$instance instanceof self)) 
        {  
            self::$instance = new self();  
        }
        return self::$instance;
    } 

	private function __clone()
	{
	}//覆盖__clone()方法，禁止克隆
 

	/**
	 * @Name: auto_connect
	 * @param:none
	 * @todu 连接memcache server
	 * @return : none
	 * add by cheng.yafei
	**/
	private function auto_connect()
	{
		$configServer = array(
								'host' => MEMCACHE_HOST, 
								'port' => MEMCACHE_PORT, 
								'weight' => 1, 
							);
		if(!$this->add_server($configServer)){
			echo '无法连接到 Memcached 服务器  Service IP: '.MEMCACHE_HOST;
		}else{
			//echo 'SUCCESS:Successfully connect to the server named '.MEMCACHE_HOST;	
		}
	}
	
	/**
	 * @Name: add_server
	 * @param:none
	 * @todu 连接memcache server
	 * @return : TRUE or FALSE
	 * add by cheng.yafei
	**/
	public function add_server($server){
		extract($server);
		return $this->m->addServer($host, $port, $weight);
	}
	
	/**
	 * @Name: add_server
	 * @todu 添加
	 * @param:$key key
	 * @param:$value 值
	 * @param:$expiration 过期时间 0 为不过期
	 * @return : TRUE or FALSE
	 * add by cheng.yafei
	**/
	public function add($key = NULL, $value = NULL, $expiration = 0)
	{
		if(is_null($expiration)){
			$expiration = MEMCACHE_EXPIRATION;
		}
		if(is_array($key))
		{
			foreach($key as $multi){
				if(!isset($multi['expiration']) || $multi['expiration'] == ''){
					$multi['expiration'] = MEMCACHE_EXPIRATION;
				}
				$this->add($this->key_name($multi['key']), $multi['value'], $multi['expiration']);
			}
		}else{
			
			switch($this->client_type){
				case MEM_C:
					$add_status = $this->m->add($this->key_name($key), $value, MEMCACHE_COMPRESSION, $expiration);
					break;
					
				default:
				case MEM_D:
					$add_status = $this->m->add($this->key_name($key), $value, $expiration);
					break;
			}
			
			return $add_status;
		}
	}
	
	/**
	 * @Name   与add类似,但服务器有此键值时仍可写入替换
	 * @param  $key key
	 * @param  $value 值
	 * @param  $expiration 过期时间 0为不过期
	 * @return TRUE or FALSE
	 * add by cheng.yafei
	**/
	public function set($key = NULL, $value = NULL, $expiration = NULL)
	{
		
		if(is_null($expiration)){
			$expiration = MEMCACHE_EXPIRATION;
		}
		if(is_array($key))
		{
			foreach($key as $multi){
				if(!isset($multi['expiration']) || $multi['expiration'] == ''){
					$multi['expiration'] = $this->config['config']['expiration'];
				}
				$this->set($this->key_name($multi['key']), $multi['value'], $multi['expiration']);
			}
		}else{
			
			switch($this->client_type){
				case MEM_C:
					$add_status = $this->m->set($this->key_name($key), $value, MEMCACHE_COMPRESSION, $expiration);
					break;
				case MEM_D:
					$add_status = $this->m->set($this->key_name($key), $value, $expiration);
					break;
			}
			return $add_status;
		}
	}
	
	/**
	 * @Name   get 根据键名获取值
	 * @param  $key key
	 * @return array OR json object OR string...
	 * add by cheng.yafei
	**/
	public function get($key = NULL)
	{
		if($this->m)
		{
			
			if(is_null($key)){
				$this->errors[] = '键值 不容许为空!';
				return FALSE;
			}
			
			if(is_array($key)){
				foreach($key as $n=>$k){
					$key[$n] = $this->key_name($k);
				}
				return $this->m->getMulti($key);
			}else{
				return $this->m->get($this->key_name($key));
			}
		}else{
			return FALSE;
		}		
	}
	
	/**
	 * @Name   delete
	 * @param  $key key
	 * @param  $expiration 服务端等待删除该元素的总时间
	 * @return true OR false
	 * add by cheng.yafei
	**/
	public function delete($key, $expiration = NULL)
	{
		if(is_null($key))
		{
			$this->errors[] = '键值 不容许为空!';
			return FALSE;
		}
		
		if(is_null($expiration))
		{
			$expiration = MEMCACHE_EXPIRATION;
		}
		
		if(is_array($key))
		{
			foreach($key as $multi)
			{
				$this->delete($multi, $expiration);
			}
		}
		else
		{
			
			return $this->m->delete($this->key_name($key), $expiration);
		}
	}
	
	/**
	 * @Name   replace
	 * @param  $key 要替换的key
	 * @param  $value 要替换的value
	 * @param  $expiration 到期时间
	 * @return none
	 * add by cheng.yafei
	**/
	public function replace($key = NULL, $value = NULL, $expiration = NULL)
	{
		if(is_null($expiration)){
			$expiration = MEMCACHE_EXPIRATION;
		}
		if(is_array($key)){
			foreach($key as $multi)	{
				if(!isset($multi['expiration']) || $multi['expiration'] == ''){
					$multi['expiration'] = $this->config['config']['expiration'];
				}
				$this->replace($multi['key'], $multi['value'], $multi['expiration']);
			}
		}else{
			
			
			switch($this->client_type){
				case MEM_C:
					$replace_status = $this->m->replace($this->key_name($key), $value, MEMCACHE_COMPRESSION, $expiration);
					break;
				case MEM_D:
					$replace_status = $this->m->replace($this->key_name($key), $value, $expiration);
					break;
			}
			
			return $replace_status;
		}
	}
	
	/**
	 * @Name   replace 清空所有缓存
	 * @return none
	 * add by cheng.yafei
	**/
	public function flush()
	{
		return $this->m->flush();
	}
	
	/**
	 * @Name   获取服务器池中所有服务器的版本信息
	**/
	public function getversion()
	{
		return $this->m->getVersion();
	}
	
	
	/**
	 * @Name   获取服务器池的统计信息
	**/
	public function getstats($type="items")
	{
		switch($this->client_type)
		{
			case MEM_C:
				$stats = $this->m->getStats($type);
				break;
			
			default:
			case MEM_D:
				$stats = $this->m->getStats();
				break;
		}
		return $stats;
	}
	
	/**
	 * @Name: 开启大值自动压缩
	 * @param:$tresh 控制多大值进行自动压缩的阈值。
	 * @param:$savings 指定经过压缩实际存储的值的压缩率，值必须在0和1之间。默认值0.2表示20%压缩率。
	 * @return : true OR false
	 * add by cheng.yafei
	**/
	public function setcompressthreshold($tresh, $savings=0.2)
	{
		switch($this->client_type)
		{
			case MEM_C:
				$setcompressthreshold_status = $this->m->setCompressThreshold($tresh, $savings=0.2);
				break;
				
			default:
				$setcompressthreshold_status = TRUE;
				break;
		}
		return $setcompressthreshold_status;
	}
	
	/**
	 * @Name: 生成md5加密后的唯一键值
	 * @param:$key key
	 * @return : md5 string
	 * add by cheng.yafei
	**/
	private function key_name($key)
	{
		return md5(strtolower($key));
	}
	
	/**
	 * @Name: 向已存在元素后追加数据
	 * @param:$key key
	 * @param:$value value
	 * @return : true OR false
	 * add by cheng.yafei
	**/
	public function append($key = NULL, $value = NULL)
	{
			
			switch($this->client_type)
			{
				
				case MEM_C:
					$append_status = $this->m->append($this->key_name($key), $value);
					break;
				
				default:
				case MEM_D:
					$append_status = $this->m->append($this->key_name($key), $value);
					break;
			}
			
			return $append_status;
		
	}


}
?>