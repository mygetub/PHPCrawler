<?php  
	// +----------------------------------------------------------------------
	// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
	// +----------------------------------------------------------------------
	// | Time: 2017年2月22日11:26:12
	// +----------------------------------------------------------------------
	// | Author: 放水的星星 https://github.com/fsdstar
	// +----------------------------------------------------------------------

	//----------------------------------
	// PHPCrawler入口文件
	//----------------------------------
	
	// 严格开发模式
	error_reporting( E_ALL & ~E_NOTICE & ~E_WARNING );

	// 设置时区
	date_default_timezone_set('Asia/Shanghai');

	// 永不超时
	ini_set('max_execution_time', 0);

	if( PHP_SAPI != 'cli' )
	{
	    exit("You must run the CLI environment\n");
	}
	
	//核心库目录
	define('CORE', dirname(__FILE__));


	//系统配置
	if( file_exists( CORE."/config/config.php" ) )
	{
	    require CORE."/config/config.php"; 
	}
	require_once(__DIR__ . '/../vendor/autoload.php');
	//加载核心文件
	require CORE.'/core/PHPCrawler.php';
	require CORE.'/core/Log.php';
	require CORE.'/core/Obtain.php';
	require CORE.'/core/Analysis.php';
	require CORE.'/core/Database.php';
	require CORE.'/core/simple_html_dom.php';
	require CORE.'/core/queue/queueInterface.php';
	require CORE.'/core/queue/defaultQueue.php';
	require CORE.'/core/queue/redisQueue.php';
	require CORE.'/core/stack/stackInterface.php';
	require CORE.'/core/stack/defaultStack.php';
	// require CORE.'/core/queue/redisQueue.php';