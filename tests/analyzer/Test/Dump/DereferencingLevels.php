<?php

namespace Test\Dump;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DereferencingLevels extends Analyzer {
    /* 2 methods */

    public function testDump_DereferencingLevels01()  { $this->generic_test('Dump/DereferencingLevels.01'); }
    public function testDump_DereferencingLevels02()  { $this->generic_test('Dump/DereferencingLevels.02'); }
}
?>