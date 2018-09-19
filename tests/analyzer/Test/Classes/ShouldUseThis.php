<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ShouldUseThis extends Analyzer {
    /* 10 methods */

    public function testClasses_ShouldUseThis01()  { $this->generic_test('Classes_ShouldUseThis.01'); }
    public function testClasses_ShouldUseThis02()  { $this->generic_test('Classes_ShouldUseThis.02'); }
    public function testClasses_ShouldUseThis03()  { $this->generic_test('Classes_ShouldUseThis.03'); }
    public function testClasses_ShouldUseThis04()  { $this->generic_test('Classes_ShouldUseThis.04'); }
    public function testClasses_ShouldUseThis05()  { $this->generic_test('Classes_ShouldUseThis.05'); }
    public function testClasses_ShouldUseThis06()  { $this->generic_test('Classes_ShouldUseThis.06'); }
    public function testClasses_ShouldUseThis07()  { $this->generic_test('Classes_ShouldUseThis.07'); }
    public function testClasses_ShouldUseThis08()  { $this->generic_test('Classes/ShouldUseThis.08'); }
    public function testClasses_ShouldUseThis09()  { $this->generic_test('Classes/ShouldUseThis.09'); }
    public function testClasses_ShouldUseThis10()  { $this->generic_test('Classes/ShouldUseThis.10'); }
}
?>