<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ForeachDontChangePointer extends Analyzer {
    /* 2 methods */

    public function testPhp_ForeachDontChangePointer01()  { $this->generic_test('Php/ForeachDontChangePointer.01'); }
    public function testPhp_ForeachDontChangePointer02()  { $this->generic_test('Php/ForeachDontChangePointer.02'); }
}
?>