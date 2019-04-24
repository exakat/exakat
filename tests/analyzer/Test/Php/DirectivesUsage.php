<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DirectivesUsage extends Analyzer {
    /* 2 methods */

    public function testPhp_DirectivesUsage01()  { $this->generic_test('Php/DirectivesUsage.01'); }
    public function testPhp_DirectivesUsage02()  { $this->generic_test('Php/DirectivesUsage.02'); }
}
?>