<?php
	require dirname(__FILE__).'/framework/init.php';
	$config = array(
		'name' => 'meiriyiwen',
		'url'	=> 'http://w.ihx.cc/',
		'method' => 'depth',
		'table' => array(
			array(
				'column' => 'title', 
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
	// $init->update_data = function($re,$page){
	// 	$re['title'] = '123213';
	// 	return $re;
	// };
	// $init->add_relation = function ($re,$mysql){
	// 	print_r($mysql::lastId());
	// };
	$init->run();
?>