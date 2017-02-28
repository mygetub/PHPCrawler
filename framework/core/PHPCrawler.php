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
		 * 解析器选择
		 */
		public static $parser = '';
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
		public static $selector = '';
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
		 * 数据库方面的变量
		 */
		// public static db_ = FALSE;

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
			$header .= "\t".self::PHPCrawler." Version :".self::VERISON."\n";
			$header .= "\tTask Number: ".self::$taskNumber."\n";
			$header .= "\tSpider is started,Please wait... \n";
			log::output($header,self::ERROR_LEVEL_1);
			/**
			 * 配置爬虫名称
			 */
			self::$name = empty($config['name'])?log::info('Deletion name','config error ',self::ERROR_LEVEL_2):$config['name'];
			/**
			 * 配置入口url
			 */
			self::$url = empty($config['url'])?log::info('Deletion URL','config error ',self::ERROR_LEVEL_2):$config['url'];
			/**
			 * 配置解析器
			 */
			self::$parser = empty($config['parser'])?log::info('Deletion parser','config error ',self::ERROR_LEVEL_2):$config['parser'];
			/**
			 * 配置解析规则
			 */
			self::$selector = empty($config['rule'])?log::info('Deletion rule','config error ',self::ERROR_LEVEL_2):$config['rule'];
			
			/**
			 * 配置抓取模式
			 */
			self::$method = empty($config['method'])?log::info('Deletion method','config error ',self::ERROR_LEVEL_2):$config['method'];

			/**
			 * 配置请求头信息
			 */
			self::$config['User-Agent'] = empty($config['User-Agent'])?self::AGENT_PC:$config['User-Agent'];
			log::info('The initialization is done ','[ info ]',self::ERROR_LEVEL_1);
		}
		/**
		 * 获得url的html
		 */
		public static function Relay(){
			// log::info('relay','[ info ]',self::ERROR_LEVEL_1);
			$Obtain = new Obtain();
			self::$html = $Obtain::Request(self::$url,self::$config);
			if(self::$html === FALSE){
				log::info( "cURL 具体出错信息: " . curl_error(Obtain::$ch),' curl error ',self::ERROR_LEVEL_2);
			}
			//web页面解析完成
			log::info('Web analysis complete','[ info ]',1);
			return;
			//log::info(self::$html,'[ info ]',self::ERROR_LEVEL_1);
		} 

		/**
		 * 选择解析器解析html并将结果返回给
		 * @param  string $html     html代码
		 * @param  [type] $selector Xpath规则
		 * @param  string $type 解析选择类型
     	 * @created time :2017年2月23日14:20:27
		 */
		public static function get_select($html,$selector,$type){
			$result = array();
			//解析选择规则
			//此时解析的为一个页面中所有的同一类的信息
			foreach($selector as $value){
				//print_r(Analysis::Parser($html,$value['selector'],$type));
				$tempInformation = Analysis::Parser($html,$value['selector'],$type);

				// print_r($tempInformation);
				for($i = 0;$i<count($tempInformation);$i++){
					$result[$i][$value['name']] = $tempInformation[$i];
					$result[$i]['Time'] =  date('Y-m-d H:i:s',time());
				}
			}
			self::$result = $result;
		}

		/**
		 * 初始化数据库
		 * @param  string $data       数据
		 */
		public static function initDatabase($data){
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

		public static function insertDatabase($name,$data){
			if(mysql::insert($name,$data)===1){
				log::info('data insert done','[ info ]',1);
			}
		}

		/**
		 * 爬虫运行中心
		 */
		public static function CrawlerRun(){
			$stime=microtime(true); 
			self::$url  = self::$url.'123'.'/';
			log::info('Crawling link:'.self::$url,'[ info ]',1);
			/*爬虫流程开始*/
			//获取url的html信息
			self::Relay();
			//提取信息
			self::get_select(self::$html,self::$selector,self::$parser);
			//存入数据库
			// print_r(self::$result);
			self::insertDatabase(self::$name,self::$result);
			/*爬虫流程结束*/
			$etime=microtime(true);//获取程序执行结束的时间
			$total=$etime-$stime;  
			log::info('Crawling link:'.self::$url.'done,execution time:'.$total,'[ info ]',1);
		}


		/**
		 * Increasing模式,主要用来分配URL
		 */
		public static function Increasing(){
			self::CrawlerRun();
		}

		/**
		 * 爬取规则选择中心
		 */
		public static function CrawlerSelector(){
			switch(self::$method){
				case 'Increasing':
					self::Increasing();
					break;

			};
		}

		/**
		 * 爬虫运行中心
		 * @return [type] [description]
		 */
		public static function run(){
			//初始化数据库
			self::initDatabase(self::$configs);
			//选择抓取模式
			self::CrawlerSelector();
						
		}


	}