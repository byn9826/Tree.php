<?php 

require(__DIR__ . '/Tree.php');
    
class Server {

  private $_server;

  public function __construct() {
    $this->_server = new swoole\http\server('127.0.0.1', '9501');
  }

  public function run() {
    $this->_server->set([
      'worker_num'=>2
    ]);
    $this->_server->on('request', [
      $this, 'onRequest'
    ]);
    $this->_server->start();
  }

  public function onRequest($request, $response) {
    Tree::load(__DIR__ . '/Dispatcher.php');
    Dispatcher::getInstance()->dispatch($request, $response);
  }

}