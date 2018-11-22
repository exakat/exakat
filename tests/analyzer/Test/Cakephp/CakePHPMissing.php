<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CakePHPMissing extends Analyzer {
    /* 1 methods */

    public function testCakephp_CakePHPMissing01()  { $this->generic_test('Cakephp/CakePHPMissing.01'); }
}
?>