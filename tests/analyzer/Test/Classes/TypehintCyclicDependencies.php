<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TypehintCyclicDependencies extends Analyzer {
    /* 1 methods */

    public function testClasses_TypehintCyclicDependencies01()  { $this->generic_test('Classes/TypehintCyclicDependencies.01'); }
}
?>