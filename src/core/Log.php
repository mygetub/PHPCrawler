<?php  
	// +----------------------------------------------------------------------
	// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
	// +----------------------------------------------------------------------
	// | Time: 2017年2月22日11:26:12
	// +----------------------------------------------------------------------
	// | Author: 放水的星星 https://github.com/fsdstar
	// +----------------------------------------------------------------------

	//----------------------------------
	// PHPCrawler日志文件
	//----------------------------------
	namespace PHPCrawler\core;
		
	class log{
		/**
		 * 报错抬头
		 * @var string
		 */
		public static $msgHeader = 'PHPCrawler ';
		/**
		 * 错误处理
		 * @param  string  $msg        错误信息
		 * @param  string  $type       错误类型
		 * @param  integer $errorLevel 错误等级
		 * @return [type]              [description]
		 */
		public static function info($msg = "",$type = "",$errorLevel = 2){
			self::msg(date("Y-m-d H:i:s "). self::$msgHeader.$type.' : '.$msg,$errorLevel);
		}
		/**
		 * 输出信息
		 * @param  string  $msg        信息内容
		 * @param  integer $errorLevel 输出等级
		 * @return [type]              [description]
		 */
		public static function output($msg = "",$errorLevel = 1){
			self::msg($msg,$errorLevel);
		}
		
		/**
		 * 输出错误
		 * @param  string $msg        错误信息
		 * @param  int $errorLevel 错误级别
		 * 用于是否继续完成后续操作
		 * 1 echo 可以继续运行后续代码
		 * 2 die 终止后续代码运行
		 * @return [type]             [description]
		 */
		public static function msg($msg,$errorLevel){
			switch($errorLevel){
				case 1:
					echo $msg."\n";
					break;
				case 2:
					die($msg."\n");
					break;
			}
		}

	}