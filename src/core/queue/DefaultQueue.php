<?php  
// +----------------------------------------------------------------------
// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
// +----------------------------------------------------------------------
// | Time: 2017年11月14日11:27:45
// +----------------------------------------------------------------------
// | Author: 放水的星星 https://github.com/fsdstar
// +----------------------------------------------------------------------


/**
 * 默认的内存队列
 */

namespace PHPCrawler\core\queue;

use PHPCrawler\core\queue\QueueInterface;

class DefaultQueue implements QueueInterface
{

	/**
     * @var int 队头指针
     */
    private $_front;
 
    /**
     * @var int 队尾指针
     */
    private $_rear;
 
    /**
     * @var array 队列数组
     */
    private $_queue;
 
    /**
     * @var int 队列实际长度
     */
    private $_queueLength;
     /**
     * Queue constructor.初始化队列
     * @param int $capacity 容量（循环队列的最大长度）
     */
	public function __construct(){
		$this->_queue = [];
        $this->_front = 0;
        $this->_rear = 0;
        $this->_queueLength = 0;
	}
	/**
     * @method 入队
     * @param mixed $url 入队的url
     * @return bool
     */
	public function inQueue($url,$key){
        $this->_queue[$key][$this->_rear] = $url;
        $this->_rear++;
        $this->_queueLength++;
        return true;
	}


	 /**
     * @method 出队
     * @return mixed|null
     */
	public function outQueue($key){
		 if (!$this->isEmpty($key)) {
            $elem = $this->_queue[$key][$this->_front];
            unset($this->_queue[$key][$this->_front]);
            $this->_front++;
            $this->_queueLength--;
            return $elem;
        }
        return null;
	}

	/**
     * @method 下一个
     * @return mixed|null
     */
	public function next($key){
		 if (!$this->isEmpty($key)) {
            $elem = $this->_queue[$key][$this->_front];
            return $elem;
        }
        return null;
	}

	/**
	 * 输出当前元素个数
	 * @return int
	 */
	public function counts($key){
		return $this->_queueLength;
	}


	/**
	 * 显示所有的队列(测试使用)
	 * @return [type] [description]
	 */
	public function show($key){
		return $this->_queue[$key];
	}

	
    public function only($key,$value){
        
    }


	 /**
     * @method 判断队列是否为空；
     * @return bool
     */
    public function isEmpty($key)
    {
        return count($this->_queue[$key]) === 0;
    }
    /**
     * @method 持久化保存队列
     * @return bool
     */
    public function save()
    {
       
    }
	/**
     * 销毁队列；
     */
	public function __destruct(){
		unset($this->_queue);
	}
}


