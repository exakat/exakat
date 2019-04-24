<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetHandlers extends Analyzer {
    /* 1 methods */

    public function testPhp_SetHandlers01()  { $this->generic_test('Php/SetHandlers.01'); }
}
?>