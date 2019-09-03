<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetClassPropertyDefinitionWithFluentInterface extends Analyzer {
    /* 1 methods */

    public function testComplete_SetClassPropertyDefinitionWithFluentInterface01()  { $this->generic_test('Complete/SetClassPropertyDefinitionWithFluentInterface.01'); }
}
?>