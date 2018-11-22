<?php

namespace Test\Php;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnsetOrCast extends Analyzer {
    /* 2 methods */

    public function testPhp_UnsetOrCast01()  { $this->generic_test('Php/UnsetOrCast.01'); }
    public function testPhp_UnsetOrCast02()  { $this->generic_test('Php/UnsetOrCast.02'); }
}
?>