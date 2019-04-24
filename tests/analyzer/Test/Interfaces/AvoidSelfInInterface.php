<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AvoidSelfInInterface extends Analyzer {
    /* 1 methods */

    public function testInterfaces_AvoidSelfInInterface01()  { $this->generic_test('Interfaces/AvoidSelfInInterface.01'); }
}
?>