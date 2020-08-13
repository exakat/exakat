<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseAttributes extends Analyzer {
    /* 1 methods */

    public function testPhp_UseAttributes01()  { $this->generic_test('Php/UseAttributes.01'); }
}
?>