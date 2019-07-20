<?php

namespace XSYD\;



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
			$this->$dbaddress = ( isset($_Mysql['MySql_address']) || array_key_exists('MySql_address', $_Mysql) ) ? $_Mysql['MySql_address'] : XSYD\Config\$sql['address'];
			$this->$dbport =  ( isset($_Mysql['MySql_port']) || array_key_exists('MySql_port', $_Mysql) ) ? $_Mysql['MySql_port'] : XSYD\Config\$sql['port'];
			$this->$dbbase =  ( isset($_Mysql['MySql_Database']) || array_key_exists('MySql_Database', $_Mysql) ) ? $_Mysql['MySql_Database'] : XSYD\Config\$sql['database'];
			$this->$dbuser =  ( isset($_Mysql['MySql_User']) || array_key_exists('MySql_User', $_Mysql) ) ? $_Mysql['MySql_User'] : XSYD\Config\$sql['user'];
			$this->$dbpass =  ( isset($_Mysql['MySql_Password']) || array_key_exists('MySql_Password', $_Mysql) ) ? $_Mysql['MySql_Password'] : XSYD\Config\$sql['password'];
			$this->$dbconn = $this->_XSYDMySQLConnetor();
    }else{
			$this->$dbaddress = XSYD\Config\$sql['address'];
      $this->$dbport = XSYD\Config\$sql['port'];
      $this->$dbbase = XSYD\Config\$sql['database'];
      $this->$dbpass = XSYD\Config\$sql['password'];
      $this->$dbuser = XSYD\Config\$sql['user'];
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

  public function _XSYDMySQLClose() : bool{
    
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
  *
  *
  */

  public  function _XSYDInitCharset() : bool{
    //默认编码utf8_general_ci
    $_Charset = 'utf8_general_ci';

    if( 'utf8' !== XSYD\Config\$sql['charset']){
       $_Charset = XSYD\Config\$sql['charset'];
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
        if( !is_string($content_types) || strlen($content_types) != strlen($content)){
          return '202';
        }
      }else{
        $content = func_get_arg( 1 );
        $_Args = false;
        if( !is_array($content) ) return '202';
      }

    	  for ($i=0; $i < strlen($content); $i++) {
          if( !$_Args ){
            $content_types .= 's';
          }
    	   if ( isset($content[$i]['content']) || array_key_exists('content', $content[$i]) ) {
    	   	//反手来一个超级加倍
    	    if( ( !isset($content[$i]['escaped']) || !array_key_exists('escaped', $content[$i]) ) || true === $content[$i]['escaped'] )
            {
              $content[$i]['content']	=  $this->_real_escape( $content[$i]['content'] );
              unset($content[$i]['escaped']);
            }
             array_push($_Array, $content[$i]['content']);
    	   }else{
            if( is_array($content[$i]) ) return '202';
             $content[$i] = $this->_real_escape( $content[$i] );
             array_push($_Array, $content[$i]);
    	   }

        }

        if( substr_count($sql, '?') != strlen($content)) return '202';       
     
        $_SQL = $this->$dbconn->prepare($sql);
       
        $ref = new ReflectionClass('mysqli_stmt');
        $ref->getMethod('bind_param')->invokeArgs($_SQL, array_merge((array)$content_types,$_Array));
      	$_SQL->execute();
        //暂时不能选择返回有无键值array（因为懒
        $_Result = $_SQL->get_result()->fetch_now();

        return isset( $_Result ) ? '199' : $_Result;
         



   }

   public function SQLInsert(array $content,array $escape_content) : bool{

   }
   public function SQLUpdate(array $content,array $escape_content) : bool{
   	
   }
    public function SQLDelete(array $content,array $escape_content) : bool{
   	
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
