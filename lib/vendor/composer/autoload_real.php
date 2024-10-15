<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit741f75f89e63c48d06e6e53d69ace7ad
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInit741f75f89e63c48d06e6e53d69ace7ad', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit741f75f89e63c48d06e6e53d69ace7ad', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit741f75f89e63c48d06e6e53d69ace7ad::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}
