<?php
/* 
* @Author: anchen
* @Date:   2015-04-11 14:59:14
* @Last Modified by:   anchen
* @Last Modified time: 2015-04-11 18:15:39
*/

class me_redis
{
    private static $mInstance=null;
    private static $rInstance=null;
    /**
     * [$objMem memcache服务对象组]
     * @var array
     */
    private  $objMem=array();

    private  $mServers=array();
    private  $rServers;
    /**
     * [$persistent 是否使用长连接]
     * @var boolean
     */
    private $persistent=false;
     /**
      * [$prefix key前缀]
      * @var string
      */
    private $prefix='';
    //数据保存的时长 默认为一天
    private $expire;


    public function __construct($mServers,$rServers)
    {
       $this->initMemcahce($mServers);
       $this->initRedis($rServers);
    }

    private function initMemcahce($mServers)
    {
        $this->mServers=$mServers;
        if(!class_exists('Memcache'))
        {
            die('没有加载Memcached.dll  php扩展!');
        }
        if(!self::$mInstance)
        {
          self::$mInstance=new Memcache();
          $this->expire=3600*24;
        }
        
        $this->addServers();
    }

    private function initRedis($rServers)
    {
        $this->rServers=$rServers;
        if(!class_exists('Redis'))
        {
            die('Redis.dll  php扩展!');
        }
        if(!self::$rInstance)
        {
          self::$rInstance=new Redis();
          if(self::$rInstance->connect($this->rServers['host'],$this->rServers['port']))
          {
             self::$rInstance->auth($this->rServers['auth']);
            
          }
        }
    }
    /**
     * [getConnect 获取memcache对象 支持多连接]
     * @return [type] [description]
     */
    public  function addServers()
    {

        foreach($this->mServers as $key=>$mV)
        {
           
           if(!self::$mInstance->addServer($mV['host'],$mV['port']))
           {
                die($mV['host'].' 没有连接成功! 端口号为'.$mV['port']);
           } 
          
        }
    }
    
     
    public function set($k,$v)
    {   
        /*****memcached******/
        if(self::$mInstance->set($k,$v,MEMCACHE_COMPRESSED,$this->expire)
           && self::$rInstance->setex($k,$this->expire,$v) )
        {

            return true;
        }
        return false;
    }

    public function get($k)
    {
        $tempValue='';
        //$tempValue=self::$mInstance->get($k);
        if(!$tempValue)
        {
            echo 'redis';
            $tempValue=self::$rInstance->get($k);
        }
       
        if($tempValue)
        {
            return $tempValue;
        }
        return false;
    }

    public function delete($k)
    {
        if(self::$mInstance->delete($k,0) 
            && self::$rInstance->delete($k))
        {
            return true;
        }
        return false;

    }

    public function getServerStatus($host,$port)
    {
        return  self::$mInstance->getServerStatus($host,$port);
    }

    public function flush()
    {
        if(self::$mInstance->flush())
        {
            return true;
        }
        return false;
    }

    public function close()
    {
        if(self::$mInstance->close())
        {
            return true;
        }
        return false;
    }
    
}
     

?>
