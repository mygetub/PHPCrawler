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
	
	namespace PHPCrawler\core;
	
	
	class Obtain
	{
		//curl资源
		public static $ch = null;
		//头信息
		public static $header = array();
		//伪造IP
		public static $IP = array();

		/**
		 * 对curl进行初始化操作
		 */
		public static function init(){

	        // 释放cURL句柄,关闭一个cURL会话
	        curl_close(self::$ch);
			// 创建一个cURL资源
			self::$ch = curl_init();
			self::$IP = self::Ip();
		}


		/**
		 * 网络请求
		 */
		public static function Request($url,$config,$cookie){
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
	        //设置Cookie信息保存在指定的文件中
	        curl_setopt(self::$ch, CURLOPT_COOKIEJAR, $cookie); 
	        //只需要设置一个秒的数量就可以  
	        curl_setopt(self::$ch, CURLOPT_TIMEOUT,5);   
	        
		    curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查  
		    curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在 
	        //post方式提交
	        //curl_setopt(self::$curl, CURLOPT_POST, 1);
	        //要提交的信息 
	        //curl_setopt(self::$curl, CURLOPT_POSTFIELDS, http_build_query($post));
	        //构造IP
	       
			$headerArr = array(); 
			foreach( self::$IP as $n => $v ) { 
			    $headerArr[] = $n .':' . $v;  
			}
	        curl_setopt (self::$ch, CURLOPT_HTTPHEADER , $headerArr );  
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
	        // return mb_convert_encoding($response, "gb2312", "utf-8");
			return $response;
		}
		public static function Ip(){
			$ip = '';
			for($i = 0;$i<4;$i++){
				$ip .= mt_rand(0,255).'.';
			}
			$ip = substr($ip,0,strlen($ip)-1);
			self::$IP['CLIENT-IP'] = $ip; 
			self::$IP['X-FORWARDED-FOR'] = $ip;
		}
	}