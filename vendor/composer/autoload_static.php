<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit73bc68337bc44d3861da9d5b8896e24f
{
    public static $files = array (
        'f084d01b0a599f67676cffef638aa95b' => __DIR__ . '/..' . '/smarty/smarty/libs/bootstrap.php',
    );

    public static $prefixLengthsPsr4 = array (
        'X' => 
        array (
            'XSYD\\User\\' => 10,
            'XSYD\\' => 5,
        ),
        'P' => 
        array (
            'Psr\\Http\\Message\\' => 17,
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'L' => 
        array (
            'League\\OAuth2\\Server\\' => 21,
            'League\\Event\\' => 13,
            'Lcobucci\\JWT\\' => 13,
        ),
        'D' => 
        array (
            'Defuse\\Crypto\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'XSYD\\User\\' => 
        array (
            0 => __DIR__ . '/..' . '/xsyd/user',
        ),
        'XSYD\\' => 
        array (
            0 => __DIR__ . '/..' . '/xsyd/tools',
        ),
        'Psr\\Http\\Message\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/http-message/src',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'League\\OAuth2\\Server\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/oauth2-server/src',
        ),
        'League\\Event\\' => 
        array (
            0 => __DIR__ . '/..' . '/league/event/src',
        ),
        'Lcobucci\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/lcobucci/jwt/src',
        ),
        'Defuse\\Crypto\\' => 
        array (
            0 => __DIR__ . '/..' . '/defuse/php-encryption/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit73bc68337bc44d3861da9d5b8896e24f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit73bc68337bc44d3861da9d5b8896e24f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
