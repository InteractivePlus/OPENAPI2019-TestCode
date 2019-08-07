<?php
require 'real_autoload.php';

$SQL = new \XSYD\DB\MySql();
$SQL->SQL('SELECT * FROM ?','s',array('test'));


?>
