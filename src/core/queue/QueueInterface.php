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
// 入队
// 出队
// 是否为空
// 下一个
// 显示队列（测试使用）
// 队列个数
// 当前内容是否在队列中
// 持久化当前队列
//----------------------------------

namespace PHPCrawler\core\queue;


interface QueueInterface
{
	public function inQueue($url,$key);
	public function outQueue($key);
	public function isEmpty($key);
	public function next($key);
	public function show($key);
	public function counts($key);
	public function save();
	public function only($key,$value);
	public function __destruct();
}