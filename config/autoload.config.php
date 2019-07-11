<?php
$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);


class AutoLoadConfig
{
	
  public static $ComposerAutoLoadFiles = array(
  	'XSYD\\UserManager\\Login' => $vendorDir.'XSYD-UserManager/Login/autoload.php',    
  	'XSYD\\UserManager\\Register' => $vendorDir.'XSYD-UserManager/Register/autoload.php',      
  	'XSYD\\Tools\\SendEmail'  => $vendorDir.'XSYD-Email/autoload.php'  

  );
}
?>