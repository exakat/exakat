<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extgettext extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extgettext01()  { $this->generic_test('Extensions_Extgettext.01'); }
    public function testExtensions_Extgettext02()  { $this->generic_test('Extensions/Extgettext.02'); }
}
?>