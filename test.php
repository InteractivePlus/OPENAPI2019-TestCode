<?php

require 'vendor/autoload.php';
//use OpenWall\PHpass\PasswordHash;
$t_hasher = new OpenWall\PHpass\PasswordHash(8, FALSE);

$correct = 'test12345';
$hash = $t_hasher->HashPassword($correct);

print 'Hash: ' . $hash . "\n";



?>

