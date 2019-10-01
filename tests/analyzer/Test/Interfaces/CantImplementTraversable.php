<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CantImplementTraversable extends Analyzer {
    /* 1 methods */

    public function testInterfaces_CantImplementTraversable01()  { $this->generic_test('Interfaces/CantImplementTraversable.01'); }
}
?>