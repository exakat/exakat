<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CakePHPUsed extends Analyzer {
    /* 1 methods */

    public function testCakephp_CakePHPUsed01()  { $this->generic_test('Cakephp/CakePHPUsed.01'); }
}
?>