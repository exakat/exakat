<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MagicProperties extends Analyzer {
    /* 1 methods */

    public function testClasses_MagicProperties01()  { $this->generic_test('Classes/MagicProperties.01'); }
}
?>