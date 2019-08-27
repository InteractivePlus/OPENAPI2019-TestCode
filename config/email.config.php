<?php

/**
* @version 0.1
* 发送email所使用的方法，有两种可填，即default和smtp。default则使用php默认mail函数
*/
define('XSYD_EMAIL_METHOD', 'smtp');

/**
* @version 0.1
* SMTP部分，如果XSYD_EMAIL_METHOD为smtp以下部分才会生效
*/
define('XSYD_EMAIL_ADDR', '');
define('XSYD_EMAIL_USER', '');
define('XSYD_EMAIL_PASSWORD', '');
define('XSYD_SMTP_AUTH', true);
define('XSYD_SMTP_SECURE', 'ssl');
define('XSYD_SMTP_PORT', '443')


?>