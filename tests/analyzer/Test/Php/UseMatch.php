<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseMatch extends Analyzer {
    /* 1 methods */

    public function testPhp_UseMatch01()  { $this->generic_test('Php/UseMatch.01'); }
}
?>