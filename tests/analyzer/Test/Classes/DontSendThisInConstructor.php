<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class DontSendThisInConstructor extends Analyzer {
    /* 1 methods */

    public function testClasses_DontSendThisInConstructor01()  { $this->generic_test('Classes/DontSendThisInConstructor.01'); }
}
?>