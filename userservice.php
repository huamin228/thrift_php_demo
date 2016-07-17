<?php
/*
*  author:huamin.zhang
*  thrift 服务端实现demo
*/
namespace user\php;

error_reporting(E_ALL);

use Thrift\ClassLoader\ThriftClassLoader;
use Thrift\Type\TConstant;
require_once __DIR__.'/lib/Thrift/ClassLoader/ThriftClassLoader.php';


$GEN_DIR = './lib/gen-php';

$loader = new ThriftClassLoader();
$loader->registerNamespace('Thrift', __DIR__ . '/lib');
$loader->registerDefinition('user', $GEN_DIR);
$loader->register();

require_once __DIR__.'/lib/gen-php/user/User.php';
require_once __DIR__.'/lib/gen-php/user/Types.php';

if (php_sapi_name() == 'cli') {
  ini_set("display_errors", "stderr");
}

use Thrift\Protocol\TBinaryProtocol;
use Thrift\Transport\TPhpStream;
use Thrift\Transport\TBufferedTransport;

/*
* user 对象实现
*/
class UserHandler implements \user\UserIf {

  public function info($num1) {
  	$user = new \user\UserProfile;
  	$user->uid = $num1;
  	$user->name = 'test'.$num1;
    return $user;
  }
  public function age(){
  	return 1;
  }
};


header('Content-Type', 'application/x-thrift');
if (php_sapi_name() == 'cli') {
  echo "\r\n";
}

//初始化服务
/*
$handler = new UserHandler();
$processor = new \user\UserProcessor($handler);
$transport = new TBufferedTransport(new TPhpStream(TPhpStream::MODE_R | TPhpStream::MODE_W));
$protocol = new TBinaryProtocol($transport, true, true);

$transport->open();
$processor->process($protocol, $protocol);
$transport->close();
*/
//提供socket服务
$handler = new UserHandler();
$processor = new \user\UserProcessor($handler);
$transport =new \Thrift\Server\TServerSocket("localhost",9090);
$transportFactory = new \Thrift\Factory\TTransportFactory();
$protocolFactory = new \Thrift\Factory\TBinaryProtocolFactory();
$server = new \Thrift\Server\TSimpleServer($processor,$transport,$transportFactory,$transportFactory,$protocolFactory,$protocolFactory);
$server->serve();
 