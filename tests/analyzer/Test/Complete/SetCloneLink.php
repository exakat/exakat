<?php

namespace Test\Complete;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class SetCloneLink extends Analyzer {
    /* 1 methods */

    public function testComplete_SetCloneLink01()  { $this->generic_test('Complete/SetCloneLink.01'); }
}
?>