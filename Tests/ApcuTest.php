<?php

/**
 * Created by PhpStorm.
 * User: Zhousilu
 * Date: 2016/8/30
 * Time: 15:52
 */

require '../vendor/autoload.php';
use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Apcu;



if (!extension_loaded('apcu')) {
    echo
        'The APCu extension is not available.'
    ;
}
if (!ini_get('apc.enable_cli')) {
    echo
        'You need to enable apc.enable_cli'
    ;
}
$adapter = new Apcu();
$adapter->setOption('ttl', 3600);
$cache = new Cache($adapter);

$cache->set('1', '100', 3600);
echo  $cache->get('1');
echo  $cache->get('1');