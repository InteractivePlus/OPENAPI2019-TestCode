<?php
namespace XSYD;

class Config{

	public static $is_composer_loaded = true;

	/**
	* @version 0.1
	* @global $sql
	* 此为数据库配置
	*
	* 注意：若charset不为utf8，请写改charset全称，例如ascii_general_ci
	* 若为utf8，请写utf8即可，无需写全称
	**/

	public static $sql = array(
                         'address' =>  'localhost', 
                         'database' => '',
                         'port'=>'3306',
                         'password'=>'zhang789',
                         'user'=>'root',
                         'charset'=>'utf8'
                         );

	public static $config = array(
		'dir' => dirname(__FILE__),
		'files' => array('salt.config.php')
	                               );



}



?>
