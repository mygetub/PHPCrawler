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
		$this->redis -> rpush ($key , serialize($url) ) ;
	}
	public function outQueue($key){
		return unserialize($this->redis->lpop($key));
	}
	public function isEmpty($key){
		return $this->redis->llen($key) === 0;
	}
	public function next($key){
		return unserialize($this->redis->lindex($key,0));
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


