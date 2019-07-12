<?php

namespace XSYD\Tools\SqlManager;


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
*   new XSYD\Tools\SqlManager\MySql('SQL_Username','SQL_Password');
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
	private $dbdata;

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
	*  @global  is_mysqli
	*  @var bool
	*
	*  判断是否支持mysqli
	*/

	public $is_mysqli = class_exists('mysqli');
	
	function __construct(
		                 $MySql_User,
		                 $MySql_Password,
		                 $MySql_Database,
                         $MySql_port = 3306,
                         $MySql_address = 'localhost'
                        )
	{
		if( isset(var) )
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
		
			if ( $this->is_mysqli() ) {
				$escaped = mysqli_real_escape_string( $this->dbconn, $string );
			} else {
				$escaped = mysql_real_escape_string( $string, $this->dbconn );
			}

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
