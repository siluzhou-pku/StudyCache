# StudyCache
desarrolla2/Cache学习


# Cache [<img alt="SensioLabsInsight" src="https://insight.sensiolabs.com/projects/5f139261-1ac1-4559-846a-723e09319a88/small.png" align="right">](https://insight.sensiolabs.com/projects/5f139261-1ac1-4559-846a-723e09319a88)

一个简单的cache库。实现了各种不同的适配器，你可以很容易的使用manager或者类似的。


[![Latest version][ico-version]][link-packagist]
[![Latest version][ico-pre-release]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-coveralls]][link-coveralls]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Sensiolabs Insight][ico-sensiolabs]][link-sensiolabs]
[![Total Downloads][ico-downloads]][link-downloads]
[![Today Downloads][ico-today-downloads]][link-downloads]
[![Gitter][ico-gitter]][link-gitter]

## 安装

### 使用 Composer

最好的安装方式是用[packagist](http://packagist.org/packages/desarrolla2/cache) 在composer.json文件中添加 `desarrolla2/cache` 

``` json
    "require": {
        // ...
        "desarrolla2/cache":  "~2.0"
    }
```

### 不使用 Composer

你也可以从github(https://github.com/desarrolla2/Cache) 上下载，但是这样子就没有自动加载器，你需要用自己的PSR-0兼容加载器注册。

## 使用


``` php
<?php

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\NotCache;

$cache = new Cache(new NotCache());

$cache->set('key', 'myKeyValue', 3600);

// later ...

echo $cache->get('key');

```

## 适配器

### Apcu

如果你希望系统中可用APC缓存，可以使用Apcu适配器
> APC，全称是Alternative PHP Cache，官方翻译叫”可选PHP缓存”。
>
> APC的缓存分两部分:系统缓存和用户数据缓存。
>
> 系统缓存是指APC把PHP文件源码的编译结果缓存起来，然后在每次调用时先对比时间标记。如果未过期，则使用缓存的中间代码运行。默认缓存 3600s(一小时),但是这样仍会浪费大量CPU时间。因此可以在php.ini中设置system缓存为永不过期(apc.ttl=0)。不过如果这样设置，改运php代码后需要重启WEB服务器。目前使用较多的是指此类缓存。
>
> 用户数据缓存由用户在编写PHP代码时用apc_store和apc_fetch函数操作读取、写入的。如果数据量不大的话，可以一试。如果数据量大，使用类似memcache此类的更加专著的内存缓存方案会更好



``` php
<?php
    
use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Apcu;

$adapter = new Apcu();
$adapter->setOption('ttl', 3600);
$cache = new Cache($adapter);

```

### File


如果你希望系统中可用其他缓存系统，或者你想要代码可移植，可以使用File适配器


``` php
<?php
    
use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\File;

$cacheDir = '/tmp';
$adapter = new File($cacheDir);
$adapter->setOption('ttl', 3600);
$cache = new Cache($adapter);

```

### Memcache

如果你希望系统中可用mencache缓存系统，可以使用mencache适配器


``` php
<?php
    
use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Memcache;

$adapter = new Memcache();
$cache = new Cache($adapter);

```
你可以在之前配置连接


``` php
<?php
    
use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Memcache;    
use \Memcache as Backend

$backend = new Backend();
// 配置

$cache = new Cache(new Memcache($backend));
```

### Memcached

和 mencache适配器一样.

### Memory

这是最快的缓存类型，因为元素是缓存在内存里的。

缓存在内存是非常不稳定的，而且当程序终止的时候内存也会消失。

同样的不同的程序之间也不可以分享内存缓存。


内存缓存都有一个“limit”选项，用于限制缓存的最大数目.

``` php
<?php
    
use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Memory;

$adapter = new Memory();
$adapter->setOption('ttl', 3600);
$adapter->setOption('limit', 200);
$cache = new Cache($adapter);

```

### Mongo

用这个适配器来缓存 cache到一个 Mongo 数据库里，需要
[(legacy) mongo 扩展](http://php.net/mongo) 或者github上
[mongodb/mongodb](https://github.com/mongodb/mongo-php-library)库.


你可以将任何数据库或者连接对象传送给构造函数。如果传送一个数据库对象，将会使用在这个数据库里的`items`  集合。



``` php
<?php

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Mongo;

$client = new MongoClient($dsn);
$database = $client->selectDatabase($dbname);

$adapter = new Mongo($database);
$adapter->setOption('ttl', 3600);
$cache = new Cache($adapter);

```

``` php
<?php

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Mongo;

$client = new MongoClient($dsn);
$database = $client->selectDatabase($dbName);
$collection = $database->selectCollection($collectionName);

$adapter = new Mongo($collection);
$adapter->setOption('ttl', 3600);
$cache = new Cache($adapter);

```

注意过期的缓存内容不会自动删除，要保证你的数据库干净，你需要创建一个
[ttl index](https://docs.mongodb.org/manual/core/index-ttl/)._


```
db.items.createIndex( { "ttl": 1 }, { expireAfterSeconds: 30 } )
```

### Mysqli

如果你希望系统中可用Mysqli缓存时使用它.

``` php
<?php

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Mysqli;

$adapter = new Mysqli();
$adapter->setOption('ttl', 3600);
$cache = new Cache($adapter);

```


``` php
<?php
    
use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Mysqli;    
use \mysqli as Backend

$backend = new Backend();
// configure it here

$cache = new Cache(new Mysqli($backend));
```

### NotCache

如果你不实现任何缓存适配器，可用用他来愚弄测试环境。


### Predis


如果你希望系统中可用redis缓存时使用它.

你需要在composer里独立添加predis



``` json
"require": {
    //...
    "predis/predis": "~1.0.0"
}
```

其他版本可能有兼容问题.

``` php
<?php

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Predis;

$adapter = new Predis();
$cache = new Cache($adapter);

```

如果想要配置predis 客户端，你需要实例化它并且传送给配置函数。


``` php
<?php

use Desarrolla2\Cache\Cache;
use Desarrolla2\Cache\Adapter\Predis;
use Predis\Client as Backend

$adapter = new Predis(new Backend($options));
$cache = new Cache($adapter);

```

## 方法

A `Desarrolla2\Cache\Cache` 对象有如下方法：

##### `delete(string $key)`
从缓存中删除数据

##### `public function get(string $key)`
从key中重新取回对应的value

##### `public function has($key)`


如果检索相应的key存在则返回value


##### `set(string $key , mixed $value [, int $ttl])`

在cache里添加一个key对应的value

##### `setOption(string $key, string $value)`

给适配器设置选项

##### `clearCache()`
清除缓存中所有多的记录


##### `dropCache()`
清除所有缓存

## 即将推出

该库即将实现其他适配器，你可以随时发送新的适配器，如果你觉得合适的话。

这是一个尚待推出的任务列表：



* Cleaning cache
* MemcachedAdapter
* Other Adapters

## doc文档

[性能测试](https://github.com/siluzhou/StudyCache/wiki/%E6%80%A7%E8%83%BD%E6%B5%8B%E8%AF%95)


