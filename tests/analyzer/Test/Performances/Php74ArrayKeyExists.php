<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Php74ArrayKeyExists extends Analyzer {
    /* 2 methods */

    public function testPerformances_Php74ArrayKeyExists01()  { $this->generic_test('Performances/Php74ArrayKeyExists.01'); }
    public function testPerformances_Php74ArrayKeyExists02()  { $this->generic_test('Performances/Php74ArrayKeyExists.02'); }
}
?>