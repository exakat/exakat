<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndefinedStaticclass extends Analyzer {
    /* 1 methods */

    public function testClasses_UndefinedStaticclass01()  { $this->generic_test('Classes/UndefinedStaticclass.01'); }
}
?>