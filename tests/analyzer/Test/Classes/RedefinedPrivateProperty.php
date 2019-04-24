<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RedefinedPrivateProperty extends Analyzer {
    /* 1 methods */

    public function testClasses_RedefinedPrivateProperty01()  { $this->generic_test('Classes/RedefinedPrivateProperty.01'); }
}
?>