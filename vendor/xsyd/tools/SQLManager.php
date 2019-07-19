<?php

namespace XSYD\Tools\;



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
			$_Mysql = array_filter($MySql);
			$this->$dbaddress = array_key_exists('MySql_address', $_Mysql) ? $_Mysql['MySql_address'] : XSYD\Config\$sql['address'];
			$this->$dbport = array_key_exists('MySql_port', $_Mysql) ? $_Mysql['MySql_port'] : XSYD\Config\$sql['port'];
			$this->$dbbase = array_key_exists('MySql_Database', $_Mysql) ? $_Mysql['MySql_Database'] : XSYD\Config\$sql['database'];
			$this->$dbuser = array_key_exists('MySql_User', $_Mysql) ? $_Mysql['MySql_User'] : XSYD\Config\$sql['user'];
			$this->$dbpass = array_key_exists('MySql_Password', $_Mysql) ? $_Mysql['MySql_Password'] : XSYD\Config\$sql['password'];
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

	public _XSYDMySQLConnetor(){


       //Please Attention!
	   //The return of mysqli will be a class object!
		return call_user_func(funtion() {
			$_mysqli = new mysqli($this->$dbaddress,$this->$dbuser,$this->$dbpass,$this->$dbbase);
			if ( mysqli_connect_errno() ) return mysqli_connect_errno();
      $this->$is_connected = true;
			return $_mysqli;
		});
	}

  public _XSYDMySQLClose(){
    
     if ( $this->$dbconn->close() ){
      $this->$is_connected = false;
      unset($this->$dbconn);
     }else{
      return '202';
     }

  }

   
   public SQL(string $sql,array $content){

   }
   /**
   *  @version 0.1
   *
   * @var $content_types 所有变量内容的类型(不支持mysqli函数可不填)
   * 参数类型（string/int的简写) ，如果type不填，默认string类型，如ssss，ssi等
   * @var $escape_content  查询所需要被过滤的参数，旨在预防SQL注入攻击，如无需过滤，请填写在$content里，必须为array，用法如下
   * @var $content 查询所需要的参数，必须为array，用法如下
   *
   *  第一种，array用法，自定义性强
   * 
   * @uses $content = array(
   *                        array('content'=>'xxxxxxx'，'usage'=>'column/table/name/value'),
   *                        array('content'=>'xxx'),
   *                         ....
   *                        )
   * @uses $content内的参数中有四个array值，分别是，content*，查询内容，（多个请用英文,连接），usage，他的作用，（有四个用法可选
   * 分别是，选取的column，选取的table，查找的名称（WHERE xx中xx），查找名称的值（WHERE xx = yy中yy））
   *
   * 注：*为必填
   * 如果usage不填，将默认使用column，table，xx，yy的顺序
   * 
   *
   * @uses The usage of $escape_content is the same as $content 
   *
   *
   * 字符类型简写如下： 
   * s = string
   * i = int
   * d = double
   * b = blob
   * 必须为简写，否则无法执行
   *
   *
   *
   *  第二种，一string，
   *  此类型使用于当$content或$escape_content内容偏少时。
   *
   * @uses 例如$content只有一个，$escape_content有三个，$content可直接填写字符串内容，但$escape_content必须为array
   * @uses array格式为SQLQuery('xx',array('xxx','xxx','xxx'))，array亦可采用第一种格式
   *
   *
   * 第三种，只有一个函数变量值
   *
   * @uses 当函数变量只有一个，只需填写一个即可，默认认为他是$escape_content
   * @uses array格式为array('xxx','xxx','xxx')，array亦可采用第一种格式
   *
   *
   * @return The Sql Query Result(Success) Otherwise,it will return the error codes.
   *
   *  More Error Codes Introductions Please visit {@link 年迈老鸽子和wey佬还没有写出来的文档.. }
   *  More character Introductions  Please visit {@link  https://www.php.net/manual/zh/mysqli-stmt.bind-param.php}
   *
   */

   public SQLQuery ($content_types,$content){

   	$_Content = array();

   	//你是不是龙鸣，脑回路有问题，参数都填错，还执行尼玛的
   	if ( empty(func_num_args()) || func_num_args() > 2) {
   		return '201';
   	}

   

    //第二种
    if ( func_num_args() >1 ){
    	if( is_array($escape_content) ){
    	  for ($i=0; $i < strlen($escape_content); $i++) {
    	   if (array_key_exists('content', $escape_content[$i]) ) {
    	   	//反手来一个超级加倍
    	   $escape_content[$i]['content']	=  $this->_real_escape( $escape_content[$i]['content'] );
    	   }else{
             $escape_content[$i] = $this->_real_escape( $escape_content[$i] );
    	   }
        }

        if( !is_array($content) ){

        }
        if( !is_array($escape_content) )
    }else{

    }
     
      if ($this->$is_mysqli){

      	if (strlen($content_types) > 4 || empty(strlen($content_types)) || strlen($content_types) == '3') return '202';

      	if(strlen($content_types) == '2'){
      		$this->$dbconn->prepare('SELECT ? From ?');
      	}else{
      		$this->$dbconn->prepare('SELECT ? From ? WHERE ?=?');
      	}
      	$this->$dbconn->bind_param(substr($content_types, $_Content['num']))
         

      	
      }else{

      }

   }

   public SQLInsert(array $content,array $escape_content) : bool{

   }
   public SQLUpdate(array $content,array $escape_content) : bool{
   	
   }
    public SQLDelete(array $content,array $escape_content) : bool{
   	
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
