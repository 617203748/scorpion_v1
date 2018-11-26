<?php
/* 
* @Author: anchen
* @Date:   2015-04-27 14:23:25
* @Last Modified by:   anchen
* @Last Modified time: 2015-05-03 10:58:18
*/
//if(!defined('RYPDINC')) exit("Request Error!!!");
    class Cache
    {
        /**********memcache 配置文件**********/
        private static $mConfig;

        private static $mCache=null;
        private function __construct(){
            echo DB_KEY;
        }

        public static function getMemcache()
        {
               self::$mConfig=$GLOBALS['cache_system'];
               if(!self::$mCache)
               {
                     /*****memcached******/
                   self::$mCache=new MyCache(self::$mConfig);
               }
                
               return self::$mCache;
         }

        /**
        * 单例方法，禁用clone
        */
        private function __clone() {}
    }
?>
