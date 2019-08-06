<?php
namespace XSYD\Password;

/**
* @version 0.1
* @author GHL(LiuXing)
* @uses XSYD\Password::GenSalt();
*/
class SecureRamdom
{
	
	public static function R($bytes=8)
	{
		if( function_exists('openssl_random_pseudo_bytes') ){
			return openssl_random_pseudo_bytes($bytes);
		}elseif ( function_exists('random_bytes') ) {
			return random_bytes($bytes);
		}else{
			return self::OldSecureRandom($bytes);
		}
	}

	public static function EasyStringRandom($bytes=8)
	{
		return bin2hex(self::R($bytes));
	}

	public static function GenSalt($count=22,$bytes=8){
		$output = self::GenSalt_Blowfish( self::EasyStringRandom($bytes) );

		if( 22 > $count ){
			$output = substr($output, $count);
		}elseif ( strlen($output) < $count ) {
			$_Random = self::EasyStringRandom( ($count-22)/2 );
			$output .= $_Random;
		}

		return $output;
	}


	/**
	* Source From @link https://www.openwall.com/phpass/
	* @see OpenWall gensalt_blowfish()
	*/
	public static function GenSalt_Blowfish($random)
	{
	
		# This one needs to use a different order of characters and a
		# different encoding scheme from the one in encode64() above.
		# We care because the last character in our encoded string will
		# only represent 2 bits.  While two known implementations of
		# bcrypt will happily accept and correct a salt string which
		# has the 4 unused bits set to non-zero, we do not want to take
		# chances and we also do not want to waste an additional byte
		# of entropy.
		$itoa64 = '!$%@?./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
		$output = '';

		$i = 0;
		do {
			$c1 = ord($random[$i++]);
			$output .= $itoa64[$c1 >> 2];
			$c1 = ($c1 & 0x03) << 4;
			if ($i >= 16) {
				$output .= $itoa64[$c1];
				break;
			}

			$c2 = ord($random[$i++]);
			$c1 |= $c2 >> 4;
			$output .= $itoa64[$c1];
			$c1 = ($c2 & 0x0f) << 2;

			$c2 = ord($random[$i++]);
			$c1 |= $c2 >> 6;
			$output .= $itoa64[$c1];
			$output .= $itoa64[$c2 & 0x3f];
		} while (1);

		return $output;

	}

	/**
	* Source From @link https://www.openwall.com/phpass/
	* @see OpenWall get_random_bytes()
	*/
	public static function OldSecureRandom($bytes=8)
	{
		$fp = @fopen('/dev/urandom','rb');
		if ($fp !== FALSE) {
   			$output = @fread($fp,$bytes);
   			@fclose($fp);
		}

		if (strlen($output) < $bytes) {
			$output = '';
			$_Random = microtime();
			for ($i = 0; $i < $bytes; $i += 16) {
				$_Random =
				    md5(microtime() . $_Random);
				$output .= md5($_Random, TRUE);
			}
			$output = substr($output, 0, $bytes);
		}

		return $output;

	}
}

?>