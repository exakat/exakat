<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class DemeterLaw extends Analyzer {
    /* 1 methods */

    public function testClasses_DemeterLaw01()  { $this->generic_test('Classes/DemeterLaw.01'); }
}
?>