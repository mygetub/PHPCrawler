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
				// 'selector'=>'//*[@id="cont"]/div[2]',
				// 'selector'=>'#cont .cont-main',
				//数据类型
				'type'=>'text',
				'parser'=>'Reg'
			),
			// array(
			// 	'name'=>'Click',
			// 	//'selector'=>'#<img[^>]+src=(["\"])(.+)\\1#isU',
			// 	'selector'=>'/html/body/div[6]/div[2]/div[1]/div[1]/div[2]/p[1]/span[3]',
			// 	//'selector'=>'.user-name',
			// 	//数据类型
			// 	'type'=>'varchar(10)'
			// ),
			// array(
			// 	'name'=>'Gender',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[1]/@title',
			// 	'type'=>'varchar(5)'
			// ),
			// array(
			// 	'name'=>'Information',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/p[1]/text()[2]',
			// 	'type'=>'varchar(50)'
			// ),
			// array(
			// 	'name'=>'StudyDuration',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[2]/em',
			// 	'type'=>'varchar(30)'
			// ),
			// array(
			// 	'name'=>'Integral',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[3]/em',
			// 	'type'=>'varchar(10)'
			// ),
			// array(
			// 	'name'=>'Experience',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[4]/em',
			// 	'type'=>'varchar(10)'
			// ),
			// array(
			// 	'name'=>'Follow',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/div/div[1]/a/em',
			// 	'type'=>'varchar(10)'
			// ),
			// array(
			// 	'name'=>'Fans',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/div/div[2]/a/em',
			// 	'type'=>'varchar(10)'
			// ),
			// array(
			// 	'name'=>'Signature',
			// 	'selector'=>'//*[@id="main"]/div[1]/div/p[2]',
			// 	'type'=>'varchar(255)'
			// )
			// array(
			// 	'name'=>'author',
				
			// 	'selector'=>'/<div class="author[\s\S]*?">[\s\S]*?<\/div>/',
			// 	'type'=>'text'
			// )
		),
		//模拟登陆
		// 'login' => array(
		// 	"LoginForm[returnUrl]" => "http%3A%2F%2Fwww.waduanzi.com%2F",
		//     "LoginForm[username]" => "用户名",
		//     "LoginForm[password]" => "密码",
		//     "yt0" => "登录",
		// ),
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
				'selector' => '//*[@id="main-content"]/section[1]/div/div[$id]/h2/a/@href'
			),
		),
		// 'first' => '.index-top-main',
		// 循环次数
		'counts' => 8,
		// 存放标签
		'topic'  => 1,
		//是否需要连接域名
		'link' => false

	);
	$init = new PHPCrawler($config);
	$init::run();
?>