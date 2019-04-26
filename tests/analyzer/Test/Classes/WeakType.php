<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WeakType extends Analyzer {
    /* 1 methods */

    public function testClasses_WeakType01()  { $this->generic_test('Classes/WeakType.01'); }
}
?>