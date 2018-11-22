<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ForeachObject extends Analyzer {
    /* 1 methods */

    public function testPhp_ForeachObject01()  { $this->generic_test('Php/ForeachObject.01'); }
}
?>