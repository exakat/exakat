<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IntegerAsProperty extends Analyzer {
    /* 1 methods */

    public function testClasses_IntegerAsProperty01()  { $this->generic_test('Classes/IntegerAsProperty.01'); }
}
?>