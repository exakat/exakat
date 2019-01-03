<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class MissingSubpattern extends Analyzer {
    /* 1 methods */

    public function testPhp_MissingSubpattern01()  { $this->generic_test('Php/MissingSubpattern.01'); }
}
?>