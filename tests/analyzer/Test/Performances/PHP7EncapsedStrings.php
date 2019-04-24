<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class PHP7EncapsedStrings extends Analyzer {
    /* 2 methods */

    public function testPerformances_PHP7EncapsedStrings01()  { $this->generic_test('Performances/PHP7EncapsedStrings.01'); }
    public function testPerformances_PHP7EncapsedStrings02()  { $this->generic_test('Performances/PHP7EncapsedStrings.02'); }
}
?>