<?php
/* 
* @Author: anchen
* @Date:   2015-04-11 14:59:14
* @Last Modified by:   anchen
* @Last Modified time: 2015-05-07 16:10:55
*/
//if(!defined('RYPDINC')) exit("Request Error!!!");
class MyCache
{
    private static $mInstance=null;
    private static $rInstance=null;
    /**
     * [$objMem memcache服务对象组]
     * @var array
     */
    private  $objMem=array();

    private  $mServers=array();
    private  $rServers=null;
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


    public function __construct($mServers=null,$rServers=null)
    {

           if(!is_null($mServers))
           {
                 /*****memcached******/
               $this->initMemcahce($mServers);
           }
            /*********redis*********/
          if(!is_null($rServers))
          {
                $this->initRedis($rServers);
          }
        
      }
     


    private function setKey($key)
    {

        return md5($key);
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
        if(is_null(self::$mInstance))
        {
            exit('memcache对象没有实例化');
        }

        foreach($this->mServers as $key=>$mV)
        {
              
        
           if(!self::$mInstance->addServer($mV['host'],$mV['port']))
           {    //生产环境关闭
                //die($mV['host'].' 没有连接成功! 端口号为'.$mV['port']);
             
           } 
          // echo (self::getServerStatus($mV['host'],$mV['port']));
        }
    }
    
     
    public function set($k,$v)
    {   

      if(empty($k))
      {
          return false;
      }
      if(empty($v))
      {
          return false;
      }
       $flag=false;
        /*****memcached******/
        if(!is_null(self::$mInstance))
        {
              
             $flag=@self::$mInstance->set($this->setKey($k),$v,MEMCACHE_COMPRESSED,$this->expire);

        }
        /*********redis*********/
        if(!is_null(self::$rInstance))
        {
           $flag=self::$rInstance->setex($this->setKey($k),$this->expire,$v);
        }
       
        return $flag;
    }

    public function get($k)
    {     
          if(empty($k))
          {
              return false;
          }
          $tempValue='';
         
          if(!is_null(self::$mInstance))
          {
             $tempValue=@self::$mInstance->get($this->setKey($k));
          }
          //redis
          if(!is_null(self::$rInstance))
          {
             if(!$tempValue)
            {
              echo 'redis';
              $tempValue=self::$rInstance->get($this->setKey($k));
            }
       }
       
       
        if($tempValue)
        {
            return $tempValue;
        }
        return false;
    }

    public function delete($k='')
    {
       if(empty($k))
       {
          exit('键值不能为空!');
       }
       $flag=false;
       if(!is_null(self::$mInstance))
        {
           
              $flag=@self::$mInstance->delete($this->setKey($k),0);
           
       }
        if(!is_null(self::$rInstance))
        {
           
             $flag=self::$rInstance->delete($this->setKey($k));;
       }
        return $flag;
    }

    public function getServerStatus($host,$port)
    {
        return  self::$mInstance->getServerStatus($host,$port);
    }

    public function flush()
    {
       if(is_null(self::$mInstance))
       {
          exit('memcache 为空');
       }
        return self::$mInstance->flush();
    }

    public function close()
    {
        if(is_null(self::$mInstance))
       {
          exit('memcache 为空');
       }
      return self::$mInstance->close();
      
    }
    
}
     

?>
