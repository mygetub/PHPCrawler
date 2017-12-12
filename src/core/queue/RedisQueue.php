<?php  

namespace PHPCrawler\core\queue;	

use PHPCrawler\core\queue\QueueInterface;
use  Predis\Client;

class RedisQueue implements QueueInterface
{

	private $redis;

	public function __construct($config){
		$this->redis = new Client($config);
	}
	public function inQueue($url,$key){
		$this->redis -> rpush ($key , $url ) ;
	}
	public function outQueue($key){
		return $this->redis->lpop($key);
	}
	public function isEmpty($key){
		return $this->redis->llen($key) === 0;
	}
	public function next($key){
		return $this->redis->lindex($key,0);
	}
	public function show($key){
		return  $this-> redis-> smembers ( $key ) ; 
	}
	public function counts($key){
		return $this->redis->llen($key);
	}

	public function save(){
		$this->redis->save();  
	}

	public function set($url,$key){
		$this->redis -> sadd  ($key , $url ) ;
	}

	public function only($key,$value){
		$temp = $this-> redis-> smembers ( $key );
		return in_array($value,$temp);
	}

	public function remove($key,$value){
		$this->redis -> srem ( $key , $value ) ;
	}



	public function __destruct(){
		//$this->redis->flushdb();  
		unset($this->redis);
	}

}


