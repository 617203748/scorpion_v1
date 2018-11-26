<?php

/***
 * 没有数据中心
 * 用法:
 * header("Content-Type: text/html; charset=utf-8");
 * ini_set('memory_limit','3072M');    // 临时设置最大内存占用为3G
 * set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
 *
 * require 'FileLock.class.php';
 * 加锁 是为了防止并发的时候产生 重复的 id
 * $lock = FileLock::getInstance();
 * $lock->lock();
 *
 * $work = new IdWork(1023);
 * $id = $work->nextId();
 * file_put_contents("id.txt", $id.PHP_EOL, FILE_APPEND);
 * $lock->unLock();
 */

/**
 * Class IdWorkNoDataCenter
 */
class IdGenerate
{
    //开始时间,固定一个小于当前时间的毫秒数即可
    const twepoch = 1506528000000; //1526659200000;//2016/9/28 0:0:0

    //机器标识占的位数
    const workerIdBits = 10;

    //毫秒内自增数点的位数
    const sequenceBits = 12;
    //服务器节点的 编号  可以有 0-1023 个 
    protected $workId = 0;

    //要用静态变量
    static $lastTimestamp = -1;
    static $sequence = 0;
    private static $instance;

    public function __construct(int $workId = 0)
    {
        //机器ID范围判断
        $maxWorkerId = -1 ^ (-1 << self::workerIdBits);
        if ($workId > $maxWorkerId || $workId < 0) {
            throw new \Exception('workerId 不能大于 ' . $maxWorkerId . ' 或不能小于 0');
        }
        //赋值
        $this->workId = $workId;
    }

    static function getInstance($workId = 0)
    {

        if (!self::$instance instanceof self) {
            self::$instance = new self($workId);
        }

        return self::$instance;
    }

    //生成一个ID
    public function nextId(int $workId = 0)
    {

        if ($workId < 0) {
            $this->workId = 0;
        }
        if ($workId > 1023) {
            $this->workId = 1023;
        }
        $timestamp = $this->timeGen();
        $lastTimestamp = self::$lastTimestamp;
        //判断时钟是否正常
        if ($timestamp < $lastTimestamp) {
            throw new Exception("Clock moved backwards.  Refusing to generate id for %d milliseconds", ($lastTimestamp - $timestamp));
        }
        //生成唯一序列
        if ($lastTimestamp == $timestamp) {
            $sequenceMask = -1 ^ (-1 << self::sequenceBits);
            self::$sequence = (self::$sequence + 1) & $sequenceMask;
            if (self::$sequence == 0) {
                $timestamp = $this->tilNextMillis($lastTimestamp);
            }
        } else {
            self::$sequence = 0;
        }
        self::$lastTimestamp = $timestamp;
        //
        //时间毫秒/数据中心ID/机器ID,要左移的位数
        $timestampLeftShift = self::sequenceBits + self::workerIdBits;
        $workerIdShift = self::sequenceBits;
        //组合3段数据返回: 时间戳.工作机器.序列
        $nextId = (($timestamp - self::twepoch) << $timestampLeftShift) | ($this->workId << $workerIdShift) | self::$sequence;

        return $nextId;
    }

    //取当前时间毫秒
    protected function timeGen()
    {
        $timestramp = (float)sprintf("%.0f", microtime(true) * 10000);
        return $timestramp;
    }

    //取下一毫秒
    protected function tilNextMillis($lastTimestamp)
    {
        $timestamp = $this->timeGen();
        while ($timestamp <= $lastTimestamp) {
            $timestamp = $this->timeGen();
        }
        return $timestamp;
    }
}