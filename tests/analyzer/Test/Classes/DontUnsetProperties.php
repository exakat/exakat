<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontUnsetProperties extends Analyzer {
    /* 2 methods */

    public function testClasses_DontUnsetProperties01()  { $this->generic_test('Classes/DontUnsetProperties.01'); }
    public function testClasses_DontUnsetProperties02()  { $this->generic_test('Classes/DontUnsetProperties.02'); }
}
?>