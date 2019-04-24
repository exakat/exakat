<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class CouldBeFinal extends Analyzer {
    /* 1 methods */

    public function testClasses_CouldBeFinal01()  { $this->generic_test('Classes/CouldBeFinal.01'); }
}
?>