<?php
	header('Content-Type:text/html;charset=utf-8');
	require dirname(__FILE__).'/framework/init.php';
	/**
	 * 定义配置参数
	 * @var array
	 */
	$config = array(
		'name' => 'articles',
		'method' => 'depth',
		'queue' => 'redisQueue',
		'urlSelector' => array(
			array(
				'selector' => '/<a id="j_chapterNext".*?>下一章<\/a>/ism',
				'parser'=>'Reg',
				'header'=> 'https:',
				'repeat'=>true,
				'childer' => array(
					array(
						'selector' => '//*[@class="text-wrap"]/div/div[1]/h3',
						'repeat'=>true
					),
				)
			)
		)
	);
	$init = new PHPCrawler($config);
	
	$init::run();
?>