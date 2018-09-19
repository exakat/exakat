<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NonPpp extends Analyzer {
    /* 8 methods */

    public function testClasses_NonPpp01()  { $this->generic_test('Classes_NonPpp.01'); }
    public function testClasses_NonPpp02()  { $this->generic_test('Classes_NonPpp.02'); }
    public function testClasses_NonPpp03()  { $this->generic_test('Classes_NonPpp.03'); }
    public function testClasses_NonPpp04()  { $this->generic_test('Classes_NonPpp.04'); }
    public function testClasses_NonPpp05()  { $this->generic_test('Classes_NonPpp.05'); }
    public function testClasses_NonPpp06()  { $this->generic_test('Classes_NonPpp.06'); }
    public function testClasses_NonPpp07()  { $this->generic_test('Classes_NonPpp.07'); }
    public function testClasses_NonPpp08()  { $this->generic_test('Classes/NonPpp.08'); }
}
?>