<?php
namespace XSYD\DB;

/**
 *  MySQL 数据库操作管理class(Pdo版本)
 *
 *  @version 0.1
 *
 *  @author GHL(LiuXing)
 *
 *  Some Source Codes From { @link Wordpress.org }
 *  All Rights Reserve
 */

class PDO_MySQL
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

	
	function __construct(argument)
	{
		# code...
	}
}


?>