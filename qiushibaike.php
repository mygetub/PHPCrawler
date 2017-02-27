<?php
	header('Content-Type:text/html;charset=utf-8');
	require dirname(__FILE__).'/framework/init.php';
	/**
	 * 定义配置参数
	 * @var array
	 */
	$config = array(
		// 当前爬虫的名字也是数据表的名字建议使用英文
		'name' => 'qiushibaike',
		// 入口url
		'url' => 'http://www.qiushibaike.com/users/',
		//解析器
		//Reg:正则表达式
		//Xpath：xpath解析器
		'parser' => 'Reg',
		//抓取规则
		//name:放到数据库中的列名
		//selector 解析器语法
		'rule' => array(
			array(
				'name'=>'Name',
				'selector'=>'/<div class="user-header-cover">[\s\S]*?<\/div>/',
				//'selector'=>'//div[@class="content"]'
				//数据类型
				'type'=>'varchar(255)'
			),
			array(
				'name'=>'Information',
				'selector'=>'/<div class="user-col-left">[\s\S]*?<\/div>/',
				//'selector'=>'//div[@class="content"]'
				//数据类型
				'type'=>'varchar(255) null'
			)
			// array(
			// 	'name'=>'author',
				
			// 	'selector'=>'/<div class="author[\s\S]*?">[\s\S]*?<\/div>/',
			// 	'type'=>'text'
			// )
		),
		//抓取模式
		/**
		 * Increasing:递增会在url连接后加数字并以一定的规律进行递增抓取
		 */
		'method' => 'Increasing'

	);
	$init = new PHPCrawler($config);
	$init::run();
?>