<?php  
	// +----------------------------------------------------------------------
	// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
	// +----------------------------------------------------------------------
	// | Time: 2017年2月22日11:26:12
	// +----------------------------------------------------------------------
	// | Author: 放水的星星 https://github.com/fsdstar
	// +----------------------------------------------------------------------

	//----------------------------------
	// PHPCrawler分析类文件
	// 用于解析获取的html代码
	// 获得新的url和我所需要的内容
	// 利用Xpath抓取
	//----------------------------------
	
	namespace PHPCrawler\core;

	use DOMDocument;
	use DOMXpath;
	
	
	class Analysis
	{
		//dom对象
		public static $dom = null;
		//xpath对象
		public static $xpath = null;


		/**
		 * 用于判断选择哪个解析器
		 * @param  string $html     html代码
		 * @param  string $selector 解析规则
		 * @param  string $type     解析器
		 */
		public static function Parser($html,$selector,$type){
			switch($type){
				case 'Xpath':
					return self::Xpath_select($html,$selector);
					break;
				case 'Reg':
					return self::Reg_select($html,$selector);
					break;
				case 'Simple':
					return self::Simple_select($html,$selector);
					break;
				default:
	            	log::info("connot find ".$type." Parser ",'[ select errors ]',2);
					breakl;
			}
		}
		/**
		 * 利用Xpath解析html
		 * @param string $html     html代码
		 * @param string $selector Xpath代码
		 */
		public static function Xpath_select($html,$selector){
			//开始解析
			// log::info('Xpath init...','[ info ]',1);
			if(!is_Object(self::$dom)){
				//生成dom对象
				self::$dom = new DOMDocument();
			}
            @self::$dom->loadHTML('<?xml encoding="utf-8">'.$html);
            self::$xpath = new DOMXpath(self::$dom);
            $elements = self::$xpath->query($selector);
	        if ($elements === false)
	        {
	        	//证明xpath语法有问题
	            log::info("the selector in the xpath(\"{$selector}\") ",'[ syntax errors ]',2);
	        }
	        $result = array();
	        foreach ($elements as $e) {
			  array_push($result,$e->nodeValue);
			}
			//解析完成
			// log::info('Xpath done','[ info ]',1);
	        // 如果只有一个元素就直接返回string，否则返回数组
	        return $result;
		}

		/**
		 * 正则表达式解析器
		 * @param string $html     html代码
		 * @param string $selector 正则表达式
     	 * @created time :2017年2月23日16:56:23
		 */
		public static function Reg_select($html,$selector){
			// preg_match_all("|<[^>]+>(.*)</[^>]+>|U", $str, $matchs);
			log::info('Reg init...','[ info ]',1);
			

			if(preg_match_all($selector, $html, $out) === false)
	        {
	            log::info("the selector in the Reg (\"{$selector}\") ",'[ syntax errors ]',2);
	        }
			log::info('Reg done','[ info ]',1);
			
	        return $out[0];

		}


		/**
		 * Simple解析器
		 * @param string $html     html代码
		 * @param string $selector Simple表达式
     	 * @created time :2017年3月1日20:45:17
		 */
		public static function Simple_select($html,$selector){
			// 新建一个Dom实例
			$simple = new simple_html_dom();
			// 从字符串中加载
			$simple->load($html);
			//读取信息
			$info = $simple->find($selector);
			$simple->clear();
			return $info;
		}


	}