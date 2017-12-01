<?php  

require_once __DIR__ . '/vendor/autoload.php';

use PHPCrawler\Crawler;

$config = array(
		'name' => 'qidian',
		'url'	=> 'https://www.qidian.com/',
		'method' => 'depth',
		// 'queue' => 'redis',
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
				'column' => 'number', 
				'type' => 0
			),array(
				'column' => 'time', 
				'type' => 0
			),
		),
		'urlSelector' => array(
			array(
				'selector' => '/\/\/book.qidian.com\/info\/\d+/ism',
				'parser'=>'Reg',
				'header'=>'https:',
				'childer' => array(
					array(
						'selector' => '/\/\/read.qidian.com\/chapter\/.{47}/ism',
						'parser'=>'Reg',
						'header'=>'https:',
						'repeat'=>true,
						'childer' => array(
							array(
								'selector'=>'//*[@class="text-wrap"]/div/div[1]/h3',
								'repeat'=>true,
								'column'=>'title',
							),array(
								'selector'=>'//*[@class="text-wrap"]/div/div[1]/div[2]/div[1]/i[1]/span',
								'repeat'=>true,
								'column'=>'number',
							),array(
								'selector'=>'//*[@class="text-wrap"]/div/div[1]/div[2]/div[1]/i[2]/span',
								'repeat'=>true,
								'column'=>'time',
							)
						)
					)
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