<?php

namespace Test\Variables;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class LocalGlobals extends Analyzer {
    /* 2 methods */

    public function testVariables_LocalGlobals01()  { $this->generic_test('Variables/LocalGlobals.01'); }
    public function testVariables_LocalGlobals02()  { $this->generic_test('Variables/LocalGlobals.02'); }
}
?>