<?php

namespace XSYD\DB;



/**
 *  MySQL数据库操作管理class
 *
 *  @version 0.1
 *
 *  @author GHL(LiuXing)
 *
 *  Some Source Codes From { @link Wordpress.org }
 *  All Rights Reserve
 */


/**
*
*   如何调用
*   
*   有以下两种调用形式
*   $mysql = new XSYD\Tools\MySql('SQL_Username','SQL_Password');
*   $mysqli->SQLQuery(xxx,xxx);
*
*   如果配置文件(sql.config.php)存在或者已经加载，则直接使用配置文件的数据库
*  
*
*
*
*/
class MySql
{

	/**
	* @global dbconn
	*  数据库连接
	*/
    
  public $dbconn;
	/**
	* @global dbuser
	*  数据库需要连接的用户
	*/
	private $dbuser;

	/**
	* @global dbpass
	*  数据库需要连接的密码
	*/
	private $dbpass;

	/**
	* @global dbpass
	*  数据库需要连接的库
	*/
	private $dbbase;

	/**
	*  @global dbport
	*  数据库需要连接的端口
	*/
	private $dbport = 3306;


	/**
	*  @global dbaddress
	*  数据库需要连接的地址
	*/
	private $dbaddress = 'localhost';

  /**
  *  @global is_connected
  *  数据库是否已经连接
  */
  public $is_connected;


	
	function __construct(array $MySql =  array(
		                 	'MySql_User' => '',
		                 	'MySql_Password'=> '',
                      'MySql_Database'=>'',
                      'MySql_port'=>'',
                      'MySql_address'=> ''
		                                        )
                      
                     )
	{

		//Use custom sql config if the fuction have some args
		//Use general.config.php sql config if no
      if( (!$this->$is_connected && !isset($this->$dbconn) && $this->$dbconn) || !is_object($this->$dbconn) ){
        if( !empty( func_num_args() ) && is_array( $MySql ) ) {
          //请不要删除isset，此目的是为了提高抗压性，因为array_key_exists效率低下
          $_Mysql = array_filter($MySql);
			    $this->$dbaddress = ( isset($_Mysql['MySql_address']) || array_key_exists('MySql_address', $_Mysql) ) ? $_Mysql['MySql_address'] : XSYD_DB_ADDR;
			    $this->$dbport =  ( isset($_Mysql['MySql_port']) || array_key_exists('MySql_port', $_Mysql) ) ? $_Mysql['MySql_port'] : XSYD_DB_PORT;
			    $this->$dbbase =  ( isset($_Mysql['MySql_Database']) || array_key_exists('MySql_Database', $_Mysql) ) ? $_Mysql['MySql_Database'] : XSYD_DB_BASE;
			    $this->$dbuser =  ( isset($_Mysql['MySql_User']) || array_key_exists('MySql_User', $_Mysql) ) ? $_Mysql['MySql_User'] : XSYD_DB_USER;
			    $this->$dbpass =  ( isset($_Mysql['MySql_Password']) || array_key_exists('MySql_Password', $_Mysql) ) ? $_Mysql['MySql_Password'] : XSYD_DB_PASSWORD;
			    $this->$dbconn = $this->_XSYDMySQLConnetor();
        }else{
			    $this->$dbaddress = XSYD_DB_ADDR;
          $this->$dbport = XSYD_DB_PORT;
          $this->$dbbase = XSYD_DB_BASE;
          $this->$dbpass = XSYD_DB_PASSWORD;
          $this->$dbuser = XSYD_DB_USER;
          $this->$dbconn = $this->_XSYDMySQLConnetor();
		    }
      }
    }

	public function _XSYDMySQLConnetor(){
     //Please Attention!
	   //The return of mysqli will be a class object!
      $_mysqli = new mysqli($this->$dbaddress,$this->$dbuser,$this->$dbpass,$this->$dbbase);
      if ( mysqli_connect_errno() ) return mysqli_connect_errno();
      $this->$is_connected = true;
      $this->_XSYDInitCharset();
      return $_mysqli;	
	 }

/**
* 关闭数据库
* @uses $SQL = XSYD\MySql();
* @uses @SQL->_Close();
* 不用数据库请关闭
*
*/
  public function _Close() : bool{
      if ( $this->$dbconn->close() ){
        $this->$is_connected = false;
        $this->$dbconn = null;
        return true;
      }else{
        return false;
      }

  }

  /**
  * @version 0.1
  * 用于初始化数据库Charset，默认utf8，可在general.config.php文件更改
  * @uses $SQL = XSYD\MySql();
  * @uses @SQL->_XSYDInitCharset();
  * 
  *
  */

  public  function _XSYDInitCharset() : bool{
    //默认编码utf8_general_ci
    $_Charset = 'utf8_general_ci';

    if( 'utf8' !== XSYD_DB_CHARSET){
       $_Charset = XSYD_DB_CHARSET;
    }else{

    /**
    * 用于检测utf8mb4是否可用(部分源码来源于Wordpress)
    * @see wpdb::has_cap()
    * @link https://github.com/WordPress/WordPress/blob/master/wp-includes/wp-db.php
    */
      
      if( version_compare( $this->_XSYDGetSQLVersion() , '5.6', '>=' ) )
      {
        $_Charset = 'utf8mb4_unicode_520_ci';
     }else{
      $client_version = mysqli_get_client_info();
      if ( false !== strpos( $client_version, 'mysqlnd' ) ) {
          $client_version = preg_replace( '/^\D+([\d.]+).*/', '$1', $client_version );
          if( version_compare( $client_version, '5.0.9', '>=' ) ){
            $_Charset = 'utf8mb4_unicode_ci';
          }
        } else {
          if( version_compare( $client_version, '5.5.3', '>=' ) ){
             $_Charset = 'utf8mb4_unicode_ci';
          }
        }
    }
  }
  
   return $this->$dbconn->set_charset( $_Charset );    
  }

    /**
    * 用于获取MySQL版本(部分源码来源于Wordpress)
    * @see wpdb::db_version()
    * @link https://github.com/WordPress/WordPress/blob/master/wp-includes/wp-db.php
    * @uses $SQL = XSYD\MySql();
    * @uses @SQL->_XSYDGetSQLVersion();
    */

  public function _XSYDGetSQLVersion(){
    return preg_replace( '/[^0-9.].*/', '' ,$this->$dbconn->server_info);
  }

   
   /**
   *  @version 0.1
   *
   * @var $content_types 所有变量内容的类型(不填默认ssss即全为字符串)
   * 参数类型（string/int的简写) ，如果type不填，默认string类型，如ssss，ssi等
   * @var $content 查询所需要的参数，必须为array，用法如下
   *
   * 
   * 绑定函数默认顺序为从左到右，因本人太懒，暂时调不了顺序（谁会需要呢
   * 注 意：escaped为是否过滤，若不填，默认为true，旨在防止SQL注入攻击，过滤会导致部分字符无法正常显示！！
   * false关闭字符过滤
   * 如果escaped都不填，可用第二种格式
   * @uses $content = array(
   *                        array('content'=>'xxxxxxx'，'escaped'=>'false'),
   *                        array('content'=>'xxx'),
   *                         ....
   *                        )
   *
   *
   * 字符类型简写如下： 
   * s = string
   * i = int
   * d = double
   * b = blob
   * 必须为简写，否则无法执行
   *
   * 第二种，只有一个函数变量值
   *
   * @uses array格式为array('xxx','xxx','xxx')，array亦可采用第一种格式，默认所有内容都要被过滤！！！
   *
   *
   * @return The Sql Query Result(Success) Otherwise,it will return the error codes.
   *
   *  More Error Codes Introductions Please visit {@link 年迈老鸽子和wey佬还没有写出来的文档.. }
   *  More character Introductions  Please visit {@link  https://www.php.net/manual/zh/mysqli-stmt.bind-param.php}
   *
   */

   public function SQL($sql,$content_types,$content){

    $_Args = true;
    $_Array = array();
    $_SQL = null;
    $_Result = null;


   	//你是不是龙鸣，脑回路有问题，参数都填错，还执行尼玛的
   	if ( empty(func_num_args()) || func_num_args() > 3) {
   		return '201';
   	}

    //第一种
    	if( func_num_args() == '3' ){
        if( !is_string($content_types) || strlen($content_types) != count($content)){
          return '202';
        }
      }else{
        $content = func_get_arg( 1 );
        $_Args = false;
        if( !is_array($content) ) return '202';
      }

    	  for ($i=0; $i < count($content); $i++) {
          if( !$_Args ){
            $content_types .= 's';
          }
    	   if ( isset($content[$i]['content']) || array_key_exists('content', $content[$i]) ) {
    	   	//反手来一个超级加倍
    	    if( ( !isset($content[$i]['escaped']) || !array_key_exists('escaped', $content[$i]) ) || true === $content[$i]['escaped'] )
            {
              $content[$i]['content']	=  $this->_real_escape( $content[$i]['content'] );
              if( isset($content[$i]['escaped']) || array_key_exists('escaped', $content[$i]) ) ) unset($content[$i]['escaped']);
            }
             array_push($_Array, $content[$i]['content']);
    	   }else{
          //当只填写array('龙鸣','WDNMD')时
             $content[$i] = $this->_real_escape( $content[$i] );
             array_push($_Array, $content[$i]);
    	   }

        }

        if( substr_count($sql, '?') != count($content) ) return '202';       
     
        $_SQL = $this->$dbconn->prepare($sql);
       
        $ref = new ReflectionClass('mysqli_stmt');
        $ref->getMethod('bind_param')->invokeArgs($_SQL, array_merge( (array)$content_types,$_Array) );
      	$_SQL->execute();

        //暂时不能选择返回有无键值array（因为懒
        $_Result = $_SQL->get_result()->fetch_now();
        $_SQL->free_result();
        return !isset( $_Result ) ? '199' : $_Result;
         



   }

  /**
   * 数据库获得数据
   *
   * @param $table string，需要获取数据的table
   * @param $col string/array,需要获取数据的col，多个请用array，一个无需用array，不填默认获取全部（需要留空）
   * @param $where array，Where条件，（格式array('xxxname'  => 'values'))
   * @param $order array，Order条件，多个请用array，一个无需用array
   * @param $length int，限制获取数据长度，不填默认不限制（无需留空）
   * @param $offset int，偏移值，不填默认不偏移（无需留空）
   * @return string 返回值 或者 boolean false
   * @uses $SQL = XSYD\MySql();
   * @uses @SQL->SQLGetRows();
   */

   public function SQLGetRows(string $table,$col=null,$where=null,$order=null,int $length=-1,int $offset=0){
      $_Args[]  = $table;
      $_Cols    = isset($col) ? '?' : '*';
      $_Result  = null;

      if( isset($col) ){
        if ( is_array($col) ){
          foreach ($col as $_value) { 
            array_push($_Args,$_value);
            if( count($data) > 1) $_Cols .= ',?';
          }
        }else{
            array_push($_Args, $col);
        }
      }

      $_SQL   = "SELECT $_Cols From ? ";
      $_Cols  = null;

      if ( !empty($where) ){
        $_Conditions = '?=?';
        foreach ($where as $_key => $_value) {
          array_push($_Args ,$_key,$_value);
          if( count($where) > 1) $_Conditions .= ' AND ?=?';
        }
        $_SQL .= 'WHERE '.$_Conditions;
        unset($_Conditions);
      }

      if( isset($order) ){
        if ( is_array($order) ){
          foreach ($order as $_value) { 
            array_push($_Args,$_value);
            if( count($data) > 1) $_Cols .= ',?';
          }
        }else{
            array_push($_Args, $order);
        }

        $_SQL  .= " ORDER BY $_Cols";
      }

      if ( '1' != $length){
        $_SQL .= " LIMIT ?";
        array_push($_Args, $length);
      }

      if ( $offset > 0){
        $_SQL .= " OFFEST ?";
        array_push($_Args, $offset);
      }

      $_Result = $this->SQL($_SQL , $_Args);

      return ( '199' !== $_Result) ? $_Result : false;



   }

   /**
   * 数据库Insert功能
   *
   * @param $table string，需要插入数据的table
   * @param $col string/array,需要插入数据的col，多个请用array，一个无需用array
   * @param $data string/array，需要插入的数据，多个请用array，一个无需用array
   * @return boolean true 或者 false
   * @uses $SQL = XSYD\MySql();
   * @uses @SQL->SQLInsert();
   */

   public function SQLInsert(string $table,$col,$data) : bool{
      $_Cols    = '?';
      $_SQL     = 'INSERT INTO ?';
      $_Args[]  = $table;

      if( is_array($col) ){
        foreach ($col as $_value) { 
          array_push($_Args,$_value);
          if( count($data) > 1) $_Cols .= ',?';
        }
      }else{
        array_push($_Args, $col);
      }

      $_SQL .= "($_Cols)";
   
      if( is_array($data) ){
        foreach ($data as $_value) { 
          array_push($_Args, $_value);
          if( count($data) > 1) $_SetArgs .= ',?';
        }
      }else{
        array_push($_Args, $data);
      }

      $_SQL .= " VALUES($_SetArgs)";

      $_Result = $this->SQL($_SQL,$_Args);
      return ( '199' !== $_Result) ? true : false;


   }

   /**
   * 数据库Update功能
   *
   * @param $table string，需要插入数据的table
   * @param $data array,需要更新的数据（格式array('xxxname'  => 'values'))
   * @param $data array，条件，（格式array('xxxname'  => 'values'))
   * @return boolean true 或者 false
   * @uses $SQL = XSYD\MySql();
   * @uses @SQL->SQLUpdate();
   * @return boolean true/false
   */

   public function SQLUpdate(string $table,array $data,array $where) : bool{
      $_Conditions = null;
      $_SQL        = null;
      $_Args[]     = $table;
      $_SetArgs    = '?=?';
        
      foreach ($data as $_key => $_value) { 
        array_push($_Args, $_key,$_value);
        if( count($data) > 1) $_SetArgs .= ',?';
      }

      $_SQL = 'UPDATE ? SET '.$_SetArgs;

   	  if ( !empty($where) ){
        $_Conditions = '?=?';
        foreach ($where as $_key => $_value) {
          array_push($_Args ,$_key,$_value);
          if( count($where) > 1) $_Conditions .= ' AND ?=?';
        }
        $_SQL .= 'WHERE '.$_Conditions;
      }

      $_Result = $this->SQL($_SQL,$_Args);
      return ( '199' !== $_Result) ? true : false;
   }

   /**
   *
   * 删除指定table
   * @param $table
   * @uses $SQL = XSYD\MySql();
   * @uses @SQL->SQLDropTable();
   * @return boolean true/false
   **/
   public function SQLDropTable(string $table) : bool{
      $_Args[] = $table;
      return ('199' !== $this->SQL('DROP TABLE IF EXISTS ?',$_Args)) ? true : false;
   	
   }


   /**
   * 数据库删除指定table
   *
   * @param $table string，需要删除数据的table
   * @param $where array,需要更新的数据（格式array('xxxname'  => 'values'))
   * @return boolean true 或者 false
   * @uses $SQL = XSYD\MySql();
   * @uses @SQL->SQLDeleteRows();
   * @return boolean true/false
   */

   public function SQLDeleteRows(string $table,array $where) : bool{
      $_Args[]     = $table;
      $_Conditions = null;
      $_SQL        = 'DELETE FROM ?';

      if (!empty($where)) {
        $_Conditions = '?=?';
        foreach ($where as $_key => $_value) {
          array_push($_Args ,$_key,$_value);
          if( count($where) > 1) $_Conditions .= ' AND ?=?';
        }
        $_SQL .= ' WHERE '.$_Conditions;
      }

      $_Result = $this->SQL($_SQL,$_Args);
      return ( '199' !== $_Result) ? true : false;


   }



/**
*  Codes From { @link Wordpress/wp-db.php }
*  Used for escaping to prevent from SQL-Injection Attacking
*  
*  @uses $this->_real_escape( string ) or 
*        A::_real_escape( string )
*/



/**
	 * Real escape, using mysqli_real_escape_string() or mysql_real_escape_string()
	 *
	 * @see mysqli_real_escape_string()
	 * @see mysql_real_escape_string()
	 * @since 2.8.0
	 *
	 * @param  string $string to escape
	 * @return string escaped
	 */
	public function _real_escape( $string ) {
		    $escaped = mysqli_real_escape_string( $this->$dbconn, $string );
		  	$escaped = addslashes( $string );
		return $this->add_placeholder_escape( $escaped );
	}


public function add_placeholder_escape( $query ) {
    /*
     * To prevent returning anything that even vaguely resembles a placeholder,
     * we clobber every % we can find.
     */
    return str_replace( '%', $this->placeholder_escape(), $query );
}
	public function placeholder_escape() {
    static $placeholder;
     if ( ! $placeholder ) {
        // If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
        $algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
        // Old WP installs may not have AUTH_SALT defined.
        $salt = defined( 'AUTH_SALT' ) && AUTH_SALT ? AUTH_SALT : (string) rand();
 
        $placeholder = '{' . hash_hmac( $algo, uniqid( $salt, true ), $salt ) . '}';
    }
 

 
    return $placeholder;
}
	/**
	 * Escape data. Works on arrays.
	 *
	 * @uses wpdb::_real_escape()
	 * @since  2.8.0
	 *
	 * @param  string|array $data
	 * @return string|array escaped
	 */
	public function _escape( $data ) {
		if ( is_array( $data ) ) {
			foreach ( $data as $k => $v ) {
				if ( is_array( $v ) ) {
					$data[ $k ] = $this->_escape( $v );
				} else {
					$data[ $k ] = $this->_real_escape( $v );
				}
			}
		} else {
			$data = $this->_real_escape( $data );
		}
		return $data;
	}
}

?>
