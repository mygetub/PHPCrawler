<p align="center"><a href="https://github.com/fsdstar/PHPCrawler" target="_blank"><img width="200"src="http://www.fsdstar.com/images/myicon.jpg"></a></p>

 <p align="center">
  <a href="#"><img src="https://img.shields.io/badge/license-MIT-4EB1BA.svg?style=flat-square" alt="Build Status"></a>
  <a href="#"><img src="https://img.shields.io/badge/version-1.0.1-red.svg?style=flat-square" alt="Sauce Test Status"></a>
</p>

### 简介

PHPCrawler 是一个简单易用的爬虫框架，下载器基于 PHP的cURL


### 特点
[//]: # (支持自定义URI过滤)
[//]: # (支持广度优先和深度优先两种爬取方式)
[//]: # (支持分布式)
[//]: # (爬取网页分为多步，每步均支持自定义动作（如添加代理、修改 user-agent 等）)
[//]: # (支持守护进程与普通两种模式（守护进程模式只支持 Linux 服务器）)
- 默认使用 cURL 进行爬取
- 支持内存、Redis 或自定义等多种队列方式
- 遵循 PSR-4 标准
- 灵活的扩展机制，可方便的为框架制作插件：自定义队列、自定义爬取方式...

### 安装

```
composer require fsdstar/phpcrawler
```

### 快速开始 

创建一个文件 start.php，包含以下内容

``` php
<?php
	require_once __DIR__ . '/vendor/autoload.php';

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
	$crawler->run();
?>
```
在命令行中执行
```
$ php start.php
```
接下来就可以看到抓取的日志了。


更多详细内容，请查看 [文档](http://doc.fsdstar.com)  

更多功能还在开发中，敬请期待￣□￣｜｜
