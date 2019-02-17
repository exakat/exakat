<?php

namespace Test\Constants;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DynamicCreation extends Analyzer {
    /* 1 methods */

    public function testConstants_DynamicCreation01()  { $this->generic_test('Constants/DynamicCreation.01'); }
}
?>