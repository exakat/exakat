<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PossibleInterfaces extends Analyzer {
    /* 1 methods */

    public function testInterfaces_PossibleInterfaces01()  { $this->generic_test('Interfaces/PossibleInterfaces.01'); }
}
?>