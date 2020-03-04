<?php
namespace XSYD\General;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * 
 */
class Email
{
	/**
	* @global mailaddr
	*  邮件目标主机
	*/
    
    public static $mailaddr;
	/**
	* @global dbuser
	*  邮件发送用户
	*/
	public static $mailuser;

	/**
	* @global dbpass
	*  邮件发送用户密码
	*/
	public static $mailpass;

	/**
	* @global dbpass
	*  邮件是否启用加密方式
	*/
	public static $mailsecure = 'ssl';

	/**
	*  @global dbport
	*  邮件主机端口
	*/
	public static $mailport = 443;

	
	function __construct(argument)
	{
		# code...
	}

	public static function send($to,$temples){

	}
}

?>
