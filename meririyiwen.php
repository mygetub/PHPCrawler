<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPCrawler\Crawler;

$config = array(
		'name' => 'articles',
		// 'count' => 8,
		'url'	=> 'http://w.ihx.cc',
		'method' => 'depth',
		'queue' => 'redis',
		'timer' => 60,
		'logFile' => 'text.txt',
		'db_config' => array(
	        'dbhost'  => '127.0.0.1',
		    'port'  => 3306,
		    'dbuser'  => 'root',
		    'dbpsw'  => 'root',
		    'dbname'  => 'crawler'
	    ),
	    'table'=>array(
	    	array('column' => 'title', 'type'=>0),
	    	array('column' => 'content', 'type'=>2),
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
						'selector' => '/<div class="entry".*?>.*?<\/div>/ism',
						'parser'=>'Reg',
						'repeat'=>true,
						'column'=>'content',
					)
				)
			)
		)
	);
	$crawler = new Crawler($config);
	// $types = [];
	// $crawler->update_data = function($re,$page){
	// 	global $types;
	// 	$re['created_at'] = date('Y-m-d H:i:s', time());
	// 	$re['updated_at'] = date('Y-m-d H:i:s', time());
	// 	$re['user_id'] = 2;
	// 	$types = $re['types'];
	// 	unset($re['types']);
	// 	return $re; 
	// };
	// $crawler->add_relation = function ($re,$mysql){
	// 	global $types;
	// 	//获得文章ID
	// 	 $articleLastId = $mysql::lastId();
	// 	//查询是否有当前标签如果有+1并返回标签ID，否则插入并返回ID
	// 	if (!empty($types)) {
	// 		$result = $mysql::find('topic',array('name'=>$types),0,1);
	// 	 	if(count($result)===0){
	// 	 		//新增内容
	// 	 		$mysql::insert('topic',array('name'=>$types,'count'=>1,'created_at'=>date('Y-m-d H:i:s', time()),'updated_at'=>date('Y-m-d H:i:s', time())));
	// 	 		//获得标签ID
	// 		 	$topicLastId = $mysql::lastId();
	// 	 	}else{
	// 	 		$mysql::update('topic',array('count'=>$result[0]['count']+1,'updated_at'=>date('Y-m-d H:i:s', time())),array('id'=>$result[0]['id']));
	// 	 		$topicLastId = $result[0]['id'];
	// 	 	}
	// 	 	//添加文章关联
	// 		$mysql::insert('article_topic',array('article_id'=>$articleLastId,'topic_id'=>$topicLastId,'created_at'=>date('Y-m-d H:i:s', time()),'updated_at'=>date('Y-m-d H:i:s', time())));
	// 	 }
		
	// };
	$crawler->run();
