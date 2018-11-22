<?php

namespace Test\ZendF;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ActionInController extends Analyzer {
    /* 2 methods */

    public function testZendF_ActionInController01()  { $this->generic_test('ZendF/ActionInController.01'); }
    public function testZendF_ActionInController02()  { $this->generic_test('ZendF/ActionInController.02'); }
}
?>