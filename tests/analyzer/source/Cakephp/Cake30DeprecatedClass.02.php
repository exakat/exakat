<?php
namespace A\B\Responses;

use Cake\Utility as Utility;
use Cake\Utility\Set as Set4;
use Cake\Utility\OtherNamespaceCrypto\Set;
use Zend\Diactoros\Stream;

class A 
{

    public function __construct()
    {
        $this->b = new Utility\Set();
        $this->c = new Utility\Set3();
        $this->c2 = new Utility\Set4;
    }
}