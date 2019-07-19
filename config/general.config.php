<?php
namespace XSYD;

class Config{

	public static $is_composer_loaded = true;

	public static $sql = array(
                         'address' =>  'localhost', 
                         'database' => '',
                         'port'=>'3306',
                         'password'=>'zhang789',
                         'user'=>'root'
                         );

	public static $config = array(
		'dir' => dirname(__FILE__),
		'files' => array('salt.config.php')
	                               );



}



?>
