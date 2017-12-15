<?php  

require_once __DIR__ . '/vendor/autoload.php';

use PHPCrawler\Crawler;

$config = array(
		'name' => 'qichezhijia',
		'url'	=> 'https://k.autohome.com.cn',
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
				'column' => 'name', 
				'type' => 0
			),array(
				'column' => 'priceD', 
				'type' => 0
			),array(
				'column' => 'priceG', 
				'type' => 0
			),array(
				'column' => 'oil', 
				'type' => 0
			),array(
				'column' => 'score',
				'type' => 0
			),array(
				'column'=>'number',
				'type'=>0
			),array(
				'column'=>'faults',
				'type'=>0
			)
		),
		'urlSelector' => array(
			array(
				'selector' => '/\/\d{1,4}\//ism',
				'parser'=>'Reg',
				'mosaic' => true,
				'childer' => array(
					array(
						'selector'=>'/html/body/div[4]/div[2]/div/a[3]',
						'repeat'=>true,
						'column'=>'name',
					),array(
						'selector'=>'//*[@id="0"]/dl/dt/div[1]/span',
						'repeat'=>true,
						'column'=>'price',
					),array(
						'selector'=>'//*[@id="0_o14T"]/div/em[1]',
						'repeat'=>true,
						'column'=>'oil',
					),array(
						'selector'=>'//*[@id="0"]/dl/dd/ul/li[2]/span[2]',
						'repeat'=>true,
						'column'=>'number',
					),array(
						'selector'=>'//*[@id="0"]/dl/dd/ul/li[2]/span[1]/span[2]',
						'repeat'=>true,
						'column'=>'score',
					),array(
						'selector'=>'/html/body/div[4]/div[5]/div/div/div[2]/a[1]/b/span',
						'repeat'=>true,
						'column'=>'faults',
					)
				)
			)
		)
	);
	$crawler = new Crawler($config);
	$crawler->update_data = function($re,$page){
		$re['number'] = substr($re['number'],1,4);
		$price = implode('-',$re['price']);
		$re['priceD'] = $price[0];
		$re['priceG'] = $price[1];
		// unset($re['price']);
		// return $re; 
		print_r($re);
		exit;
	};
	// $crawler->add_relation = function ($re,$mysql){
	// 	print_r($mysql::lastId());
	// };
	$crawler->run();