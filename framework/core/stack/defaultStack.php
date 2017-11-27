<?php  
// +----------------------------------------------------------------------
// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
// +----------------------------------------------------------------------
// | Time: 2017年11月14日11:27:45
// +----------------------------------------------------------------------
// | Author: 放水的星星 https://github.com/fsdstar
// +----------------------------------------------------------------------


/**
 * 默认的内存栈
 */
class defaultStack implements stackInterface
{

	/**
     * @var int 栈头指针
     */
    private $_front;
 
    /**
     * @var int 栈尾指针
     */
    private $_rear;
 
    /**
     * @var array 栈数组
     */
    private $_stack;
 
    /**
     * @var int 栈实际长度
     */
    private $_stackLength;
     /**
     * stack constructor.初始化栈
     * @param int $capacity 容量（循环栈的最大长度）
     */
	public function __construct(){
		$this->_stack = [];
        $this->_front = 0;
        $this->_rear = 0;
        $this->_stackLength = 0;
	}
	/**
     * @method 入栈
     * @param mixed $url 入栈的url
     * @return bool
     */
	public function inStack($url){
        $this->_stack[$this->_front] = $url;
        $this->_front++;
        $this->_stackLength++;
        return true;
	}
	 /**
     * @method 出栈
     * @return mixed|null
     */
	public function outStack(){
		 if (!$this->isEmpty()) {
            $elem = $this->_stack[$this->_front];
            unset($this->_stack[$this->_front]);
            $this->_front--;
            $this->_stackLength--;
            return $elem;
        }
        return null;
	}
	 /**
     * @method 判断栈是否为空；
     * @return bool
     */
    public function isEmpty()
    {
        return $this->_stackLength === 0;
    }
	/**
     * 销毁栈；
     */
	public function __destruct(){
		unset($this->_stack);
	}
}


