<?php
	header('Content-Type:text/html;charset=utf-8');
	require dirname(__FILE__).'/framework/init.php';
	/**
	 * 定义配置参数
	 * @var array
	 */
	$config = array(
		'name' => 'articles',
		'url' => 'https://www.qidian.com/',
		'cookie'=>'',
		'method' => 'depth',
		'queue' => 'redisQueue',
		'urlSelector' => array(
			array(
				'selector' => '/\/\/book.qidian.com\/info\/\d+/ism',
				'parser'=>'Reg',
				'header'=> 'https:',
				'childer' => array(
					array(
						'selector' => '//*[@id="readBtn"]/@href',
						'repeat'=>true,
						'childer'=>array()
					),
				)
			)
		)
	);
	$init = new PHPCrawler($config);
	
	$init::run();
?>