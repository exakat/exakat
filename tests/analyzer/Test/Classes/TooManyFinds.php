<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TooManyFinds extends Analyzer {
    /* 4 methods */

    public function testClasses_TooManyFinds01()  { $this->generic_test('Classes/TooManyFinds.01'); }
    public function testClasses_TooManyFinds02()  { $this->generic_test('Classes/TooManyFinds.02'); }
    public function testClasses_TooManyFinds03()  { $this->generic_test('Classes/TooManyFinds.03'); }
    public function testClasses_TooManyFinds04()  { $this->generic_test('Classes/TooManyFinds.04'); }
}
?>