<?php

namespace MyNamespace;

class MyLog implements \Psr\Log\LoggerInterface {
    public function emergency($message, array $context = array()) {}
    public function alert($message, array $context = array()) {}
    public function critical($message, array $context = array()) {}
    public function error($message, array $context = array()) {}
    public function warning($message, array $context = array()) {}
    public function notice($message, array $context = array()) {}
    public function info($message, array $context = array()) {}
    public function debug($message, array $context = array()) {}
    public function log($level, $message, array $context = array()) {}
}

class MyLog2 implements \Psr\Log\LoggerInterface2 {}

class MyLog3 implements Psr\Log\LoggerInterface {}


?>