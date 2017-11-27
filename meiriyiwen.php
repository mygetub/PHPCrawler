<?php
	require dirname(__FILE__).'/framework/init.php';
	$config = array(
		'name' => 'meiriyiwen',
		'url'	=> 'http://w.ihx.cc/',
		'method' => 'depth',
		// 'queue' => 'redisQueue',
		'table' => array(
			array(
				'column' => 'title', 
				//字段类别(0:字符串，1:数字，2:文章，3:日期)
				'type' => 0
			),array(
				'column' => 'author', 
				'type' => 0
			),array(
				'column' => 'time', 
				'type' => 0
			),
		),
		'urlSelector' => array(
			array(
				'selector' => '/http:\/\/w.ihx.cc\/.{1,20}\/\d+.html/ism',
				'parser'=>'Reg',
				'childer' => array(
					array(
						'selector' => '//*[@id="the-post"]/div[2]/h1/span',
						'repeat'=>true,
						'column'=>'title',
					),array(
						'selector' => '//*[@id="the-post"]/div[2]/p/span[1]/a',
						'repeat'=>true,
						'column'=>'author',
					),array(
						'selector' => '//*[@id="the-post"]/div[2]/p/span[3]/text()',
						'repeat'=>true,
						'column'=>'time',
					),
				)
			)
		)
	);
	$init = new PHPCrawler($config);
	$init::run();
?>