<?php
/**
 * Created by PhpStorm.
 * User: Zhousilu
 * Date: 2016/8/30
 * Time: 15:55
 */

require '../vendor/autoload.php';

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\File;

$cacheDir = '../tmp';
$adapter = new File($cacheDir);
$adapter->setOption('ttl', 3600);
$cache = new Cache($adapter);


$cache->set('1', '100', 3600);
echo  $cache->get('1');
echo  $cache->get('1');