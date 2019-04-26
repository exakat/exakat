<?php

namespace Test\Composer;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class IsComposerClass extends Analyzer {
    /* 5 methods */

    public function testComposer_IsComposerClass01()  { $this->generic_test('Composer_IsComposerClass.01'); }
    public function testComposer_IsComposerClass02()  { $this->generic_test('Composer_IsComposerClass.02'); }
    public function testComposer_IsComposerClass03()  { $this->generic_test('Composer_IsComposerClass.03'); }
    public function testComposer_IsComposerClass04()  { $this->generic_test('Composer_IsComposerClass.04'); }
    public function testComposer_IsComposerClass05()  { $this->generic_test('Composer/IsComposerClass.05'); }
}
?>