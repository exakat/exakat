<?php
namespace A\B\Responses;

use Cake\Utility\Set as Set2;
use Cake\Utility\OtherNamesapceCrypto\Set;
use Cake\Utility\Set as C;
use Zend\Diactoros\Stream;

class A 
{

    public function __construct()
    {
        $this->b = new Set2();
        $this->c = new C;
    }
}