<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PhpinfoUsage extends Analyzer {
    /* 1 methods */

    public function testStructures_PhpinfoUsage01()  { $this->generic_test('Structures_PhpinfoUsage.01'); }
}
?>