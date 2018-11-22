<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Cake30DeprecatedClass extends Analyzer {
    /* 2 methods */

    public function testCakephp_Cake30DeprecatedClass01()  { $this->generic_test('Cakephp/Cake30DeprecatedClass.01'); }
    public function testCakephp_Cake30DeprecatedClass02()  { $this->generic_test('Cakephp/Cake30DeprecatedClass.02'); }
}
?>