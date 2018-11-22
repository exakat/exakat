<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class NewOnFunctioncallOrIdentifier extends Analyzer {
    /* 3 methods */

    public function testClasses_NewOnFunctioncallOrIdentifier01()  { $this->generic_test('Classes/NewOnFunctioncallOrIdentifier.01'); }
    public function testClasses_NewOnFunctioncallOrIdentifier02()  { $this->generic_test('Classes/NewOnFunctioncallOrIdentifier.02'); }
    public function testClasses_NewOnFunctioncallOrIdentifier03()  { $this->generic_test('Classes/NewOnFunctioncallOrIdentifier.03'); }
}
?>