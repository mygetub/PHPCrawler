<?php  

require_once __DIR__ . '/../../vendor/autoload.php';

use PHPCrawler\Crawler;

$config = array(
		'name' => 'meiriyiwen',
		'url'	=> 'http://w.ihx.cc/',
		'method' => 'depth',
		'queue' => 'redis',
		'db_config' => array(
	        'dbhost'  => '127.0.0.1',
		    'port'  => 3306,
		    'dbuser'  => 'root',
		    'dbpsw'  => 'root',
		    'dbname'  => 'discussion'
	    ),
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
	$crawler = new Crawler($config);
	// $crawler->update_data = function($re,$page){
	// 	$re['title'] = '123213';
	// 	return $re; 
	// };
	// $crawler->add_relation = function ($re,$mysql){
	// 	print_r($mysql::lastId());
	// };
	$crawler->run();