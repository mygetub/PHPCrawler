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
		'url' => 'https://www.qidian.com',
		//解析器
		//Reg:正则表达式解析器
		//Xpath：xpath解析器
		//Simple:使用simple_html_dom解析器
		//抓取规则
		//name:放到数据库中的列名
		//selector 解析器语法
		/*'rule' => array(
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
		),*/
		'cookie'=>'',
		//抓取模式
		/**
		 * Increasing:单页面爬取
		 * Breadth:广度遍历，根据爬取url规则由广入深
		 * Depth:深度遍历,根据爬取url规则由浅入深
		 */
		'method' => 'depth',
		/**
		 * url的爬取规则
		 */
		'urlSelector' => array(
			array(
				// 'selector' => '/\/\/book.qidian.com\/info\/\d+/ism',
				'selector' => '/html/body/div[2]/div[6]/div[3]/div/ul/li[1]/strong/a/@href',
				'header'=> 'https:',
				// 'mosaic' => true,
				// 'parser'=>'Reg',
				'childer' => array(
					array(
						'selector' => '//*[@id="readBtn"]/@href',
						// 'mosaic' => true,
						'header'=> 'https:',
						'repeat'=>true,
						'childer' => array(
							array(
								'selector' => '/<h3 class="j_chapterName".*?>.*?<\/h3>/ism',
								'parser'=>'Reg',
								'repeat'=>true
							),
							array(
								'selector' => '/<span class="j_updateTime".*?>.*?<\/span>/ism',
								'parser'=>'Reg',
								'repeat'=>true
							),
							array(
								'selector'=>'//*[@id="j_chapterNext"]/@href',
								'header'=> 'https:',
								'repeat'=>true,
								//下一页连接
								'next'=> true
							)
							
							// array(
							// 	//此时证明我需要很多
							// 	'selector' => '//*[@id="comicContain"]/li[$id]/img/@src',
							// 	'field' => 'images',
							// 	//分隔符
							// 	'delimiter' => '|'
							// ),
							// array(
							// 	'selector' => '//*[@id="mainControlNext"]/@href',
							// 	//确定是需要加上首页url
							// 	'mosaic' => true,
							// 	//重复使用的规则
							// 	'repeat' => true

							// )
						)
					)
				)
			)
		)
	);
	$init = new PHPCrawler($config);
	$init->customRenderData = 1;
	$init::run();
?>