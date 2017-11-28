<?php  
	// +----------------------------------------------------------------------
	// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
	// +----------------------------------------------------------------------
	// | Time: 2017年2月22日11:26:12
	// +----------------------------------------------------------------------
	// | Author: 放水的星星 https://github.com/fsdstar
	// +----------------------------------------------------------------------

	//----------------------------------
	// PHPCrawler主文件
	//----------------------------------
	class PHPCrawler
	{
		/**
		 * 版本号
		 */
		const VERISON = '1.0.0';
		/**
		 * 爬虫框架名
		 */
		const PHPCrawler = "PHPCrawler";
		/**
		 * 错误等级1
		 */
		const ERROR_LEVEL_1 = 1;
		/**
		 * 错误等级2
		 */
		const ERROR_LEVEL_2 = 2;

		 /**
	     * 爬虫爬取网页所使用的浏览器类型: pc、ios、android
	     * 默认类型是PC
	     */
	    const AGENT_PC = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36";
	    const AGENT_IOS = "Mozilla/5.0 (iPhone; CPU iPhone OS 9_3_3 like Mac OS X) AppleWebKit/601.1.46 (KHTML, like Gecko) Version/9.0 Mobile/13G34 Safari/601.1";
	    const AGENT_ANDROID = "Mozilla/5.0 (Linux; U; Android 6.0.1;zh_cn; Le X820 Build/FEXCNFN5801507014S) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/49.0.0.0 Mobile Safari/537.36 EUI Browser/5.8.015S";


	    /**
	     * 爬虫名称
	     */
	    public static $name = '';
		/**
		 * 入口连接
		 */
		public static $url = '';
		/**
		 * 抓取模式
		 */
		public static $method = '';
		/**
		 * 爬取次数
		 */
		public static $taskNumber = 0;
		/**
		 * url中的html
		 */
		public static $html = '';
		/**
		 * 获得选取规则
		 */
		// public static $selector = '';
		/**
		 * 首页规则URL
		 */
		public static $urlSelector = '';
		/**
		 * 是否需要连接域名
		 */
		public static $link = false;
		/**
		 * 请求头信息
		 * @var array
		 */
		public static $config = array();
		public static $configs = array();
		/**
		 * 解析出来的结果
		 * @var array
		 */
		public static $result = array();
		/**
		 * 队列模式
		 * @var array
		 */
		public static $urlQueue = [];
		public static $urlQueueKey = 'url';
		/**
		 * 匹配规则
		 * @var array
		 */
		public static $selectorQueue = [];
		public static $selectorQueueKey = 'selector';
		/**
		 * html栈
		 * @var array
		 */
		public static $htmlQueue = [];
		public static $htmlQueueKey = 'html';


		public static $currentUrl = '';

		/**
		 * 已爬url的个数
		 */
		public static $urls = 0;
		/**
		 * 数据库方面的变量
		 */
		// public static db_ = FALSE;

		//钩子函数
		public static $hooks = [
			'beforeDownloadPageHooks',
			'downloadPageHooks',
			'afterDownloadPageHooks',
			'discoverUrlHooks',
			'afterDiscoverHooks',
		];
		public $update_data = '';
		public $add_relation = '';

		function __construct($config){
			self::$configs = $config;
			/**
			 * 提示爬虫信息
			 * [ 豆瓣读书 Spider ]
			 * PHPSpider Version: 3.0.4
			 * Task Number: 1
			 * Spider is started,Please wait...
			 */
			$header = "[ ".$config['name']." Spider ] \n";
			$header .= self::PHPCrawler." Version :".self::VERISON."\n";
			$header .= "Task Number: ".self::$taskNumber."\n";
			$header .= "Spider is started,Please wait... \n";
			log::output($header,self::ERROR_LEVEL_1);
			/**
			 * 配置爬虫名称
			 */
			self::$name = empty($config['name'])?log::info('Deletion name','config error ',self::ERROR_LEVEL_2):$config['name'];
			self::$url = empty($config['url'])?'':$config['url'];
			/**
			 * 配置url解析规则
			 */
			self::$urlSelector = empty($config['urlSelector'])?'':$config['urlSelector'];
			/**
			 * 配置抓取模式
			 */
			self::$method = empty($config['method'])?'':$config['method'];
			/**
			 * 配置link
			 */
			self::$link = $config['link'];

			/**
			 * 配置请求头信息
			 */
			self::$config['User-Agent'] = empty($config['User-Agent'])?self::AGENT_PC:$config['User-Agent'];
			/**
			 * 配置匹配规则队列模式
			 * 配置URL队列模式
			 */
			self::$selectorQueue = empty($config['queue'])?new defaultQueue() : new $config['queue'];
			 self::$urlQueue = empty($config['queue'])?new defaultQueue() : new $config['queue'];
			self::$htmlQueue = empty($config['queue'])?new defaultQueue() : new $config['queue'];
			log::info('The initialization is done ','[ info ]',self::ERROR_LEVEL_1);
		}
		/**
		 * 获得url的html
		 * return 返回具体的Html代码
		 */
		public function Relay($url){
			$html = Obtain::Request($url,self::$config,self::$configs['cookie']);
			if($html === FALSE){
				log::info( "cURL error info: " . curl_error(Obtain::$ch),' curl error ',self::ERROR_LEVEL_1);
			}
			//web页面解析完成
			log::info('Web analysis complete','[ info ]',1);
			return $html;
		} 

		/**
		 * 选择解析器解析html并将结果返回给
		 * @param  string $html     html代码
		 * @param  [type] $selector Xpath规则
		 * @param  string $type 解析选择类型(默认Xpath)
     	 * @created time :2017年2月23日14:20:27
		 */
		public function get_select($html,$selector,$type = 'Xpath'){
			$tempInformation = Analysis::Parser($html,$selector,$type);
			log::info($type.' done ','[ info ]',1);
			return $tempInformation;
		}

		/**
		 * 初始化数据库
		 * @param  string $data       数据
		 */
		public function initDatabase($data){
			//数据库是否创建连接
			if(!isset(mysql::$pdo)){
				mysql::connect($GLOBALS['db']);
			}
			//判断是否创建了爬虫数据表
			if(mysql::tablesNum($data['name']) == 1){
				//self::db_ = TRUE;
				mysql::createDatabase($data);
			}
			//pdo原先的进程死亡再开一个进程
			mysql::connect($GLOBALS['db']);
		} 

		public function insertDatabase($name,$data){
			if(mysql::insert($name,$data)===1){
				// mysql::autoElo('article_topic',self::$topic);
				log::info('data insert done','[ info ]',1);
			}
		}


		/**
		 * 爬取规则选择中心
		 */
		public  function CrawlerSelector(){
			if(self::$method){
				call_user_func(array(PHPCrawler , self::$method),self::$urlSelector);
			}
		}

		/**
		 * 爬虫运行中心
		 * 根据URL回调钩子函数
		 * @return [type] [description]
		 */
		public function run(){
			$this->startWorker();
			while (!self::$urlQueue->isEmpty(self::$urlQueueKey)) {
				foreach (self::$hooks as $value) {
					// call_user_func(array(PHPCrawler,$value));
					call_user_func(array(PHPCrawler,$value));
				}
			}
			$this->endWorker();
						
		}

		/**
		 * 开始工作准备阶段
		 * 初始化数据库
		 * 初始化爬取模式
		 */
		public function startWorker(){
			//--------------------------------------------------------------------------------
	        // 运行前验证
	        //--------------------------------------------------------------------------------

	        // 检查PHP版本
	        if (version_compare(PHP_VERSION, '5.3.0', 'lt')) 
	        {
	            log::info('PHP 5.3+ is required, currently installed version is: ' . phpversion(),' PHPCrawler system ',self::ERROR_LEVEL_2);
	        }

	        // 检查CURL扩展
	        if(!function_exists('curl_init'))
	        {
	            log::info('The curl extension was not found',' PHPCrawler system ',self::ERROR_LEVEL_2);
	        }
			//初始化数据库
			$this->initDatabase(self::$configs);
			//初始化抓取规则
			$this->CrawlerSelector();
			// 将首页放置于队列中
			if(!empty(self::$url)){
				self::$urlQueue->inQueue(self::$url,self::$urlQueueKey);	
			}
		}

		/**
		 * 获得页面的html之前所做的事
		 * 出队URL
		 */
		public function beforeDownloadPageHooks(){
			log::output('urls:'.self::$urls,self::ERROR_LEVEL_1) ;
			log::output('Surplus urls:'.self::$urlQueue->counts(self::$urlQueueKey),self::ERROR_LEVEL_1) ;
			log::output('Speed of progress:'.(round(self::$urls/(self::$urls+self::$urlQueue->counts(self::$urlQueueKey)),2)*100).'%',self::ERROR_LEVEL_1) ;
			log::output('task:'.self::$selectorQueue->next(self::$selectorQueueKey)['task'],self::ERROR_LEVEL_1) ;
			// print_r(self::$selectorQueue->show());
			self::$currentUrl = self::$urlQueue->outQueue(self::$urlQueueKey);
			log::output('url:'.self::$currentUrl,self::ERROR_LEVEL_1) ;

			self::$urls++;
			
		}
		/**
		 * 获取到需要爬取的URL则进行获取HTML
		 * 并将其插入html栈中
		 */
		public function downloadPageHooks(){
			self::$html = $this->Relay(self::$currentUrl);
			// echo self::Relay(self::$currentUrl);
		}
		/**
		 * 获得页面的html之后所做的事
		 * 判断该selector是否带有html
		 * 如果没有则进行抓取信息入库
		 */
		public function afterDownloadPageHooks(){
			
			// 确定一直找到连接选择器才退出
			$option =  self::$selectorQueue->next(self::$selectorQueueKey);
			$re = [];
			for ($i = 0 ;$i < self::$selectorQueue->counts(self::$selectorQueueKey);$i++) {

				if($option['childer']|| $option['next']){
					return ;
				}
				//如果是正常的匹配则如果页面存在则，抓取信息
				// $html = self::$htmlQueue->outQueue(self::$name);
				$option =  self::$selectorQueue->outQueue(self::$selectorQueueKey);
				if(!empty(self::$html)){
					$result = $this->get_select(self::$html,$option['selector'],empty($option['parser'])?'Xpath':$option['parser']);
					//如果为空则证明没有后续了则直接跳出
					if(count($result)===0){
						return ;
					}
					//如果连续
					$re[$option['column']] = $result[0];
					// print_r($result);
					// call_user_func($this->update_dataHooks,$result);
				}
				// self::insertDatabase(self::$name,$re);
				$temp =  self::$selectorQueue->next(self::$selectorQueueKey);
				if($option['repeat']){
					self::$selectorQueue->inQueue($option,self::$selectorQueueKey);
				}
				//如果两个相等则证明没有更多的选择了
				if(empty(array_diff($option,$temp))){
					return;
				}
				$option = $temp;

			}
			$page['url'] = self::$currentUrl;
			//可以执行数据渲染回调函数对数据进行自定义渲染
			$return = call_user_func($this->update_data,$re,$page);
			//如果没有return则不变
			if (isset($return)) $re = $return;
			//插入数据库
			$this->insertDatabase(self::$name,$re);
			//执行添加关联回调函数
			call_user_func($this->add_relation,$re,new mysql());
			

			
		}
		/**
		 * 发现连接的规则我只需要拿出一个如果下面有children则确定为链接选择器
		 * @return [type] [description]
		 */
		public function discoverUrlHooks(){
				$option = self::$selectorQueue->next(self::$selectorQueueKey);
				if($option['childer'] || $option['next']){
					if(!$option['repeat']){
						// self::$selectorQueue->inQueue($option);
						$option = self::$selectorQueue->outQueue(self::$selectorQueueKey);
					}
					// $html = self::$htmlQueue->outQueue(self::$name);
					if(empty(self::$html)){
						return ;
					}
					$url = $option['mosaic']?self::$url:$option['header'];
					//这里以后可以优化
					$result = array_unique($this->get_select(self::$html,$option['selector'],empty($option['parser'])?'Xpath':$option['parser']));
					print_r($result);
					foreach ($result as $value) {
						self::$urlQueue->inQueue($url.$value,self::$urlQueueKey);
					}

					
					
				}
				
			// }
		}
		public function afterDiscoverHooks(){
			// log::output('5',self::ERROR_LEVEL_1) ;
		}

		/**
		 * 结束
		 * 初始化数据库
		 * 初始化爬取模式
		 */
		public function endWorker(){
			//初始化数据库
			// self::initDatabase(self::$configs);
			//选择抓取模式
			// self::CrawlerSelector();
			log::info('The PHPCrawler is end ','[ info ]',self::ERROR_LEVEL_1);
		}



		/**
		 * 广度遍历爬取规则
		 * 爬取所有的规则队列
		 */
		public function breadth(){
			//先读取首页的主要URL
			// self::homepaegsUrl(self::$url);
			echo 'breadth';
		}
		/**
		 * 深度遍历爬取规则
		 * 爬取所有的规则队列
		 */
		public function depth($urlSelector){
			foreach ($urlSelector as $value) {
				$option = [];
				foreach ($value as $key => $item) {
					if($key != 'childer'){
						$option[$key] = $item;
					}
				}
				$option['task'] = self::$taskNumber;
				// $option['status'] = 0;
				//判断该选择器是否为连接选择器(1:是，0不是)
				$option['childer'] = empty($value['childer'])?0:1;
 				self::$selectorQueue->inQueue($option,self::$selectorQueueKey);
				self::$taskNumber++;
				$this->depth($value['childer']);
				self::$taskNumber--;
			}
		}
	}
