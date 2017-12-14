<?php  
	// +----------------------------------------------------------------------
	// | PHPCrawler [ 一个关于PHP爬虫的框架 ]
	// +----------------------------------------------------------------------
	// | Time: 2017年2月22日11:26:12
	// +----------------------------------------------------------------------
	// | Author: 放水的星星 https://github.com/fsdstar
	// +----------------------------------------------------------------------

	//----------------------------------
	// PHPCrawler数据库操作类文件
	//----------------------------------
	

	namespace PHPCrawler\core;

	use PDO;

	class Mysql
	{
		
		//pdo对象
		public static $pdo = null;

		//type类型
		public static $types = array(
			'varchar(255)',
			'int',
			'text',
			'DATETIME',
		);



		/**
		 * 创建pdo连接
		 * @return [type] [description]
		 */
		public static function connect($config){
			try{ 
				$dsn = "mysql:host=".$config['dbhost'].";dbname=".$config['dbname'];
				self::$pdo = new PDO($dsn, $config['dbuser'], $config['dbpsw']);
				self::$pdo->exec("SET names utf8");
  			 	self::$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			}catch(PDOException $e) { 
				log::info($e->getMessage(),"[ database config error ]",2); 
			}
			return;
		}


		/**
		 * 
		 * @param  [type] $name [数据库表的名字]
		 * @param  [type] $data [增加的信息]
		 * @return [type]       [返回增加了几行数据]
		 */
		public static function insert($name,$data){
			$keys = array();
			$values = array();
			foreach ($data as $key => $value) {
				array_push($keys, $key);
				array_push($values, is_string($value)? '\''.addslashes($value).'\'':addslashes($value));
			}
			$sql = 'INSERT INTO '.$name.' ('.implode(', ', $keys).') VALUES ('.implode(', ', $values).')';
			return self::sqlRun($sql);
		}

		/**
		 * 添加自动关联
		 * @return [type] [description]
		 */
		// public static function autoElo($name,$topic_id){
		// 	$data = array('article_id' => self::$pdo->lastInsertId(),
		// 				  'topic_id'=>$topic_id,
		// 				  'created_at' => date('Y-m-d H:i:s',time()),
		// 				  'updated_at' => date('Y-m-d H:i:s',time()),);
		// 	$keys = array();
		// 	$values = array();
		// 	foreach ($data as $key => $value) {
		// 		array_push($keys, $key);
		// 		array_push($values, is_string($value)? '\''.addslashes($value).'\'':addslashes($value));
		// 	}
		// 	$sql = 'INSERT INTO '.$name.' ('.implode(', ', $keys).') VALUES ('.implode(', ', $values).')';
		// 	return self::sqlRun($sql);
		// }

		/**
		 * [delete 删除数据]
		 * @param  [type] $table [表的名称]
		 * @param  [type] $where [删除条件]
		 * @return [type]        [返回删除了几行数据]
		 */
		public static function delete($table,$where = ""){
			$sql = 'delete from '.$table;
			if($where === ""){
				return self::sqlRun($sql);
			}
			$sql.=' where ';
			$sql = self::myforeach($sql,$where,' and ');
			return self::sqlRun($sql);
		}

		/**
		 * [update 修改一个指定的数据]
		 * @param  [type] $table [数据表名]
		 * @param  [type] $array [更新字段]
		 * @param  [type] $where [判断条件]
		 * @return [type]        [返回更新了几行数据]
		 */
		public static function update($table,$array,$where){
			$sql = 'update '.$table.' set ';
			$sql = self::myforeach($sql,$array,' , ');
			$sql .= ' where ';
			$sql = self::myforeach($sql,$where);
			return self::sqlRun($sql); 
		}

		/**
		 * [find 查询数据]
		 * @param  [type] $table [数据库表名]
		 * @param  [type] $where [查询条件]
		 * @param  [type] $limit [查询多少条数据]
		 * @param  [type] $sort  [排序规则]
		 * @return [type]        [返回查询的结果]
		 */
		public static function find($table,$where,$limitStart,$limitEnd,$sort = ""){
			$sql = 'select * from '.$table;
			if($where != ""){
				$sql.=' where ';
				$sql = self::myforeach($sql,$where,' and ');
			}
			if($sort != ""){
				$sql.=' ORDER BY id '.$sort;
			}
			if(is_integer($limitStart) && is_integer($limitEnd)){
				$sql .= ' limit '.$limitStart.','.$limitEnd;
			}

			return self::sqlRead($sql);
		}
		

		/**
		 * [line 获得表中有多少条数据]
		 * @param  [type] $table [description]
		 * @return [type]        [description]
		 */
		public static function line($table){
			$sql = 'select count(id) from '.$table;
			return self::sqlRead($sql);
		}

		/**
		 * [myforeach 连接数组]
		 * @param  [type] $sql       [传递的数组]
		 * @param  [type] $array     [传递的数组]
		 * @param  string $separator [传递的数组]
		 * @return [type]            [返回连接好的字符串]
		 */
		public static function myforeach($sql,$array,$separator = ""){
			foreach ($array as $key => $value) {
				$sql .= $key.' = '.(is_string($value)? '"'.$value.'"':$value).$separator;
			}
			return substr($sql,0,strlen($sql) - strlen($separator));
		}


		/**
		 * [sqlRun 针对没有结果集合返回的操作]
		 * @param  [type] $sql [运行的sql语句]
		 * @return [type]      [返回影响行数]
		 */
		public static function sqlRun($sql){
			try {
				return self::$pdo->exec($sql);
			} catch (Exception $e) {
				return self::err($e->getMessage());
			}
		}
		/**
		 * [sqlRun 用于有记录结果返回的操作]
		 * @param  [type] $sql [运行的sql语句]
		 * @return [type]      [返回结果]
		 */
		public static function sqlRead($sql){
			try {
				$arrayName = array();
				$res =  self::$pdo->query($sql);
				while($row = $res->fetch(PDO::FETCH_ASSOC)){
				  	$arrayName[] = $row;
				}
				return $arrayName;
			} catch (Exception $e) {
				return self::err($e->getMessage());
			}
		}

		/**
		 * 判断数据库是否存在
		 * @param  string $tablesNum 数据库名称
		 * @return int  数据库个数
		 */
		public static function tablesNum($tablesName){
			$result = self::$pdo->query("SHOW TABLES LIKE '". $tablesName."'");
			return count($result->fetchAll());
  
		}
		public static function createDatabase($column){
			/**
			 * create table tableName{
			 * 		tableNameId int primary key auto_increment not null unqiue,
			 * 		tableNameTime DateTime ,
			 * 		tableName type ,
			 * 		...
			 * }
			 */
			$sql = 'create table '.$column["name"].'(id int primary key auto_increment not null UNIQUE,';
			for($i = 0;$i<count($column['table']);$i++){
				$sql.= $column['table'][$i]['column'].' '.self::$types[$column['table'][$i]['type']].' , ';
			}
			$sql = substr($sql,0,strlen($sql)-2).' ) ';
			self::sqlRun($sql); 
		}
		/**
		 * 用于处理数据库操作时错误情况
		 * @param  [type] $error [错误信息]
		 */
		public static function err($error){
			log::info("对不起，您的操作有误，错误原因为：".$error,'[ database error ]',2);
		}


		public static function lastId(){
			return self::$pdo->lastInsertId();
		}

	}



?>