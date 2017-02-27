<?php  
	// +----------------------------------------------------------------------
	// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
	// +----------------------------------------------------------------------
	// | Time: 2017年2月22日11:26:12
	// +----------------------------------------------------------------------
	// | Author: 放水的星星 https://github.com/fsdstar
	// +----------------------------------------------------------------------

	//----------------------------------
	// PHPCrawler获取类文件
	// 用于获取的html代码
	// 该版本主要使用curl进行网页获取
	//----------------------------------
	class Obtain
	{
		//curl资源
		public static $ch = null;
		//头信息
		public static $header = array();


		/**
		 * 对curl进行初始化操作
		 */
		public static function init(){
			// 创建一个cURL资源
			self::$ch = curl_init();
		}


		/**
		 * 网络请求
		 */
		public static function Request($url,$config){
			//请求初始化
			log::info('Request init...','[ info ]',1);
			self::init();
			// 设置请求选项, 包括具体的url
			// 需要获取的 URL 地址
	        curl_setopt(self::$ch, CURLOPT_URL, $url);
	        //获取的信息以字符串返回，而不是直接输出。
	        curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
	        //启用时会将头文件的信息作为数据流输出。
	        curl_setopt(self::$ch, CURLOPT_HEADER, 0);
	        // curl_setopt(self::$ch, CURLOPT_ENCODING, 'utf-8');


	        //设置请求头信息
	        if ($config)
	        {
	            $headers = array();
	            foreach ($config as $k=>$v) 
	            {
	                $headers[] = $k.": ".$v;
	            }
	            curl_setopt( self::$ch, CURLOPT_HTTPHEADER, $headers );
	        }

	        // 执行一个cURL会话并且获取相关回复
	        $response = curl_exec(self::$ch);
	        //curl_getinfo(self::$ch);

	        // 释放cURL句柄,关闭一个cURL会话
	        curl_close(self::$ch);
	        // return mb_convert_encoding($response, "gb2312", "utf-8");;
			return $response;
		}
	}