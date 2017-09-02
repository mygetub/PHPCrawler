<?php
	header('Content-Type:text/html;charset=utf-8');
	require dirname(__FILE__).'/framework/init.php';
	/**
	 * 定义配置参数
	 * @var array
	 */
	$config = array(
		// 当前爬虫的名字也是数据表的名字建议使用英文
		'name' => 'articles',
		// 入口url
		'url' => 'http://w.ihx.cc/',
		//解析器
		//Reg:正则表达式解析器
		//Xpath：xpath解析器
		//Simple:使用simple_html_dom解析器
		'parser' => 'Reg',
		//抓取规则
		//name:放到数据库中的列名
		//selector 解析器语法
		'rule' => array(
			array(
				'name'=>'title',
				// 'selector' => '/<h1>.*?<\/h1>/ism',
				'selector'=>'//*[@id="the-post"]/div[2]/h1/span',
				// 'selector'=>'#cont h1',
				//数据类型
				'type'=>'varchar(255)',
				'parser'=>'Xpath'
			),
			array(
				'name'=>'content',
				'selector' => '/<div class="article_text".*?>.*?<\/div>/ism',
				// 'selector'=>'#cont .cont-main',
				//数据类型
				'type'=>'text',
				'parser'=>'Reg'
			)
		),
		'cookie'=>'',
		//抓取模式
		/**
		 * Increasing:递增会在url连接后加数字并以一定的规律进行递增抓取
		 * Breadth:广度遍历，根据首页的连接依次遍历
		 */
		'method' => 'Breadth',
		/**
		 * 爬取第一套url然后通过队列来进行
		 */
		'first' => array(
			array(
				'selector' => '//*[@id="main-content"]/section[1]/div/div[1]/h2/a/@href'
			)
		),
		// 'first' => '.index-top-main',
		'prefix' => '',
		'counts' => 1,
		'topic'  => 1
	);
	$init = new PHPCrawler($config);
	$init::run();
?>