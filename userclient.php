<?php
/*
*  author:huamin.zhang
*  thrift 服务端实现demo
*/
namespace user\php;

error_reporting(E_ALL);
require_once __DIR__.'/lib/Thrift/ClassLoader/ThriftClassLoader.php';


use Thrift\ClassLoader\ThriftClassLoader;

$GEN_DIR = 'lib/gen-php';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/lib');
$loader->registerDefinition('user', $GEN_DIR);
$loader->register();


require_once __DIR__.'/lib/gen-php/user/User.php';
require_once __DIR__.'/lib/gen-php/user/Types.php';


use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TSocket;
use Thrift\Transport\THttpClient;
use Thrift\Transport\TBufferedTransport;
use Thrift\Exception\TException;

try {
  //if (array_search('--http', $argv)) {
    //$socket = new THttpClient('localhost', 80, '/thrift/userservice.php');
  //} else {
    $socket = new TSocket('localhost', 9090);
  //}
  $transport = new TBufferedTransport($socket, 1024, 1024);
  $protocol = new TBinaryProtocol($transport);
  
  $client = new \user\UserClient($protocol);

  $transport->open();
  //var_dump($transport);exit;

  $user = $client->info(1);
  var_dump($user);exit;
  

  $transport->close();

} catch (TException $tx) {
  print 'TException: '.$tx->getMessage()."\n";
}

?>