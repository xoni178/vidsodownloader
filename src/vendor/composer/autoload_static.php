<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitb6a59dbc5c47e63a5de79c87e2586088
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitb6a59dbc5c47e63a5de79c87e2586088::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitb6a59dbc5c47e63a5de79c87e2586088::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitb6a59dbc5c47e63a5de79c87e2586088::$classMap;

        }, null, ClassLoader::class);
    }
}
