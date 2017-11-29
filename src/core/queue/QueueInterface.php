<?php 

// +----------------------------------------------------------------------
// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
// +----------------------------------------------------------------------
// | Time: 2017年11月13日13:25:12
// +----------------------------------------------------------------------
// | Author: 放水的星星 https://github.com/fsdstar
// +----------------------------------------------------------------------

//----------------------------------
// PHPCrawler队列接口文件
// 用于用户自定义其他类型的接口文件（内置内存和redis）
// 需要实现的方法
// 初始化队列
// 入队
// 出队
// 当前队列个数
// 下一个
// 清空队列
// 销毁队列
// 根据传入id输出对应的值
//----------------------------------

namespace PHPCrawler\core\queue;


interface QueueInterface
{
	public function inQueue($url,$key);
	public function outQueue($key);
	public function isEmpty($key);
	public function next($key);
	public function show();
	public function counts($key);
	public function __destruct();
}