<?php


	 define('XSYD_CONFIG_PATH', dirname(__FILE__));
	 define('XSYD_BASE_PATH', dirname(dirname(__FILE__)));

	/**
	* @version 0.1
	* @global $sql
	* 此为数据库配置
	*
	* 注意：若charset不为utf8，请写改charset全称，例如ascii_general_ci
	* 若为utf8，请写utf8即可，无需写全称
	**/

	/* $sql = array(
                         'address' =>  'localhost', 
                         'database' => '',
                         'port'=>'3306',
                         'password'=>'zhang789',
                         'user'=>'root',
                         'charset'=>'utf8'
                         );*/

	 define('XSYD_DB_ADDR', 'localhost');
	 define('XSYD_DB_BASE','xsyd');
	 define('XSYD_DB_PORT', '3306');
	 define('XSYD_DB_PASSWORD', 'zhang789');
	 define('XSYD_DB_USER', 'root');
	 define('XSYD_DB_CHARSET', 'utf8');


	 $_Files = array('salt.config.php');






?>
