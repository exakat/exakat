<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Coalesce extends Analyzer {
    /* 1 methods */

    public function testPhp_Coalesce01()  { $this->generic_test('Php/Coalesce.01'); }
}
?>