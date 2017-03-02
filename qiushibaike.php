<?php
	header('Content-Type:text/html;charset=utf-8');
	require dirname(__FILE__).'/framework/init.php';
	/**
	 * 定义配置参数
	 * @var array
	 */
	$config = array(
		// 当前爬虫的名字也是数据表的名字建议使用英文
		'name' => 'imooc',
		// 入口url
		'url' => 'http://www.imooc.com/u/',
		//解析器
		//Reg:正则表达式解析器
		//Xpath：xpath解析器
		//Simple:使用simple_html_dom解析器
		'parser' => 'Xpath',
		//抓取规则
		//name:放到数据库中的列名
		//selector 解析器语法
		'rule' => array(
			array(
				'name'=>'Name',
				//'selector'=>'#<img[^>]+src=(["\"])(.+)\\1#isU',
				'selector'=>'//*[@id="main"]/div[1]/div/h3/span',
				//'selector'=>'.user-name',
				//数据类型
				'type'=>'varchar(255)'
			),
			array(
				'name'=>'Gender',
				'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[1]/@title',
				'type'=>'varchar(5)'
			),
			array(
				'name'=>'Information',
				'selector'=>'//*[@id="main"]/div[1]/div/p[1]/text()[2]',
				'type'=>'varchar(50)'
			),
			array(
				'name'=>'StudyDuration',
				'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[2]/em',
				'type'=>'varchar(30)'
			),
			array(
				'name'=>'Integral',
				'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[3]/em',
				'type'=>'varchar(10)'
			),
			array(
				'name'=>'Experience',
				'selector'=>'//*[@id="main"]/div[1]/div/p[1]/span[4]/em',
				'type'=>'varchar(10)'
			),
			array(
				'name'=>'Follow',
				'selector'=>'//*[@id="main"]/div[1]/div/div/div[1]/a/em',
				'type'=>'varchar(10)'
			),
			array(
				'name'=>'Fans',
				'selector'=>'//*[@id="main"]/div[1]/div/div/div[2]/a/em',
				'type'=>'varchar(10)'
			),
			array(
				'name'=>'Signature',
				'selector'=>'//*[@id="main"]/div[1]/div/p[2]',
				'type'=>'varchar(255)'
			)
			// array(
			// 	'name'=>'author',
				
			// 	'selector'=>'/<div class="author[\s\S]*?">[\s\S]*?<\/div>/',
			// 	'type'=>'text'
			// )
		),
		//模拟登陆
		'login' => array(
			"LoginForm[returnUrl]" => "http%3A%2F%2Fwww.waduanzi.com%2F",
		    "LoginForm[username]" => "用户名",
		    "LoginForm[password]" => "密码",
		    "yt0" => "登录",
		),
		'cookie'=>'',
		//抓取模式
		/**
		 * Increasing:递增会在url连接后加数字并以一定的规律进行递增抓取
		 */
		'method' => 'Increasing'

	);
	$init = new PHPCrawler($config);
	$init::run();
?>