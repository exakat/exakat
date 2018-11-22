<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Extdba extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extdba01()  { $this->generic_test('Extensions_Extdba.01'); }
}
?>