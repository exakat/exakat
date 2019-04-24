<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class BasenameSuffix extends Analyzer {
    /* 4 methods */

    public function testStructures_BasenameSuffix01()  { $this->generic_test('Structures/BasenameSuffix.01'); }
    public function testStructures_BasenameSuffix02()  { $this->generic_test('Structures/BasenameSuffix.02'); }
    public function testStructures_BasenameSuffix03()  { $this->generic_test('Structures/BasenameSuffix.03'); }
    public function testStructures_BasenameSuffix04()  { $this->generic_test('Structures/BasenameSuffix.04'); }
}
?>