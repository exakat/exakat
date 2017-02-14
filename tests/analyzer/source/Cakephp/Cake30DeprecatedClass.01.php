<?php
namespace A\B\Responses;

use Cake\Utility\Set as Set2;
use Cake\Utility\Set as Set4;
use Cake\Utility\OtherNamespaceCrypto\Set;
use Cake\Utility\Set as C;
use Zend\Diactoros\Stream;

class A 
{

    public function __construct()
    {
        $this->b = new Set2();
        $this->c = new Set3();
        $this->c2 = new Set4;
        $this->d = new C;
    }
}