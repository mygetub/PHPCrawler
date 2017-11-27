



<?php  
	

use  Predis\Client;

class redisQueue implements queueInterface
{

	private $redis;

	public function __construct(){
		$this->redis = new Client($GLOBALS['redis']);
	}
	public function inQueue($url,$key){
		$this->redis -> lpush ($key , serialize($url) ) ;
	}
	public function outQueue($key){
		return unserialize($this->redis->rpop($key));
	}
	public function isEmpty($key){
		return $this->redis->llen($key) === 0;
	}
	public function next($key){
		return unserialize($this->redis->lindex($key,1));
	}
	public function show(){}
	public function counts($key){
		return $this->redis->llen($key);
	}
	public function __destruct(){
		$this->redis->flushdb();  
		unset($this->redis);
	}

}


