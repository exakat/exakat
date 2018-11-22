<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ThrowInDestruct extends Analyzer {
    /* 1 methods */

    public function testClasses_ThrowInDestruct01()  { $this->generic_test('Classes/ThrowInDestruct.01'); }
}
?>