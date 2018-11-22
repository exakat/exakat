<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class RaisedAccessLevel extends Analyzer {
    /* 4 methods */

    public function testClasses_RaisedAccessLevel01()  { $this->generic_test('Classes/RaisedAccessLevel.01'); }
    public function testClasses_RaisedAccessLevel02()  { $this->generic_test('Classes/RaisedAccessLevel.02'); }
    public function testClasses_RaisedAccessLevel03()  { $this->generic_test('Classes/RaisedAccessLevel.03'); }
    public function testClasses_RaisedAccessLevel04()  { $this->generic_test('Classes/RaisedAccessLevel.04'); }
}
?>