<?php

namespace App\Core;

use Redis;

class RedisClient
{
	private $redis;
	private $host = 'localhost';
	private $port = '6389';
	
	public function  __construct()
	{
		$this->connect();
	}
	
	/**
	 * 连接redis 
	 */
	public function connect()
	{
		$this->redis = new Redis();
		$res = $this->redis->connect( $this->host, $this->port );
		if($res==false){			
			$this->redis = null;
		}
		$this->redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP); //必须使用此序列化参数 否则sadd数组 无法读取结果 消息推送功能无法正常运行
		return $res;
	}
	
	/**
	 * 向集合中添加一个元素
	 * @param string $key
	 * @param mixed $val
	 */
	public function set_sAdd($key, $val)
	{
		if($this->redis==null){
			return false;
		}
		return $this->redis->sAdd($key , $val);
	}
	
	/**
	 * 取出集合中的所有元素
	 * @param string $key
	 */
	public function set_sMembers($key)
	{
		if($this->redis==null){
			return false;
		}
		return $this->redis->sMembers($key);
	}
	
	/**
	 * 删除集合中的一个元素
	 * @param string $key
	 * @param mixed $val
	 */
	public function set_sRem($key, $val)
	{
		if($this->redis==null){
			return false;
		}
		return $this->redis->sRem($key , $val);
	}
	
	/**
	 * 删除一个元素
	 */
	public function del($key)
	{
		if($this->redis==null){
			return false;
		}
		$this->redis->delete($key);
	}
	
	/**
	 * 获取单个元素值
	 */
	public function get($key) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->get ( $key );
	}
	
	/**
	 * 添加单个元素
	 */
	public function set($key, $val) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->set ( $key, $val );
	}
	
	/**
	 * 添加单个元素并设置过期时间
	 */
	public function setex($key, $expire, $val) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->setex ( $key, $expire, $val );
	}
	
	/**
	 * 获取hash元素值
	 */
	public function hget($hash, $key) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->hGet ( $hash, $key );
	}
	
	/**
	 * 设置hash元素
	 */
	public function hset($hash, $key, $val) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->hSet ( $hash, $key, $val );
	}
	
	/**
	 * 设置多个hash元素
	 *
	 * @param $hash key
	 * @param $params array('k1'=>$v1,
	 *        	'k2'=>$v2);
	 */
	public function hmset($hash, $params) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->hMset ( $hash, $params );
	}
	
	/**
	 * 获取hash中多个值
	 *
	 * @param $hash key
	 * @param $array array(k1,
	 *        	k2);
	 */
	public function hmget($hash, $array) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->hMget ( $hash, $array );
	}
	
	/**
	 * 获取所有hash值
	 */
	public function hgetall($key) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->hGetAll ( $key );
	}
	
	/**
	 * 删除hash表中某一键值
	 * */
	public function hdel($hash, $key) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->hDel ( $hash, $key );
	}
	
	/**
	 * 对值作自增操作
	 * */
	public function incr($key) {
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->incr($key);
	}
	
	/**
	 * 查询$hkey中是否存在$key键
	 * */
	public function hexists($hkey, $key){
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->hExists($hkey, $key);
	}
	
	/**
	 * 将值推入队列左端
	 * */
	public function lpush($key, $value){
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->lPush($key, $value);
	}
	
	/**
	 * 从队列左端取出一个值
	 * */
	public function lpop($key){
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->lPop($key);
	}
	
	/**
	 * 将值推入队列右端
	 * */
	public function rpush($key, $value){
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->rPush($key, $value);
	}
	
	/**
	 * 从队列右端取出一个值
	 * */
	public function rpop($key){
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->rPop($key);
	}
	
	/**
	 * 设置key的生存时间 单位:秒
	 * */
	public function expire($key, $sec){
		if ($this->redis==null) {
			return false;
		}
		return $this->redis->expire($key, $sec);
	}
}