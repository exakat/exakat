<?php

namespace Test\Performances;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CsvInLoops extends Analyzer {
    /* 3 methods */

    public function testPerformances_CsvInLoops01()  { $this->generic_test('Performances/CsvInLoops.01'); }
    public function testPerformances_CsvInLoops02()  { $this->generic_test('Performances/CsvInLoops.02'); }
    public function testPerformances_CsvInLoops03()  { $this->generic_test('Performances/CsvInLoops.03'); }
}
?>