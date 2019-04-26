<?php

namespace Test\Composer;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsComposerNsname extends Analyzer {
    /* 2 methods */

    public function testComposer_IsComposerNsname01()  { $this->generic_test('Composer/IsComposerNsname.01'); }
    public function testComposer_IsComposerNsname02()  { $this->generic_test('Composer/IsComposerNsname.02'); }
}
?>