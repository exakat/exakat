<?php
namespace A\B\Responses;

use Cake\Utility\Crypto\Mcrypt as Mcrypt2;
use Cake\Utility\OtherNamesapceCrypto\Mcrypt;
use Cake\Utility\Crypto\Mcrypt as C;
use Zend\Diactoros\Stream;

class A 
{

    public function __construct()
    {
        $this->b = new Mcrypt2();
        $this->c = new C;
    }
}