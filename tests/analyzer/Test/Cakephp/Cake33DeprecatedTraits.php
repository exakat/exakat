<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Cake33DeprecatedTraits extends Analyzer {
    /* 2 methods */

    public function testCakephp_Cake33DeprecatedTraits01()  { $this->generic_test('Cakephp/Cake33DeprecatedTraits.01'); }
    public function testCakephp_Cake33DeprecatedTraits02()  { $this->generic_test('Cakephp/Cake33DeprecatedTraits.02'); }
}
?>