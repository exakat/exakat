<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class RedefinedDefault extends Analyzer {
    /* 4 methods */

    public function testClasses_RedefinedDefault01()  { $this->generic_test('Classes/RedefinedDefault.01'); }
    public function testClasses_RedefinedDefault02()  { $this->generic_test('Classes/RedefinedDefault.02'); }
    public function testClasses_RedefinedDefault03()  { $this->generic_test('Classes/RedefinedDefault.03'); }
    public function testClasses_RedefinedDefault04()  { $this->generic_test('Classes/RedefinedDefault.04'); }
}
?>