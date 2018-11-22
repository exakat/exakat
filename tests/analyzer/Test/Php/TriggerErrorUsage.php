<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class TriggerErrorUsage extends Analyzer {
    /* 1 methods */

    public function testPhp_TriggerErrorUsage01()  { $this->generic_test('Php/TriggerErrorUsage.01'); }
}
?>