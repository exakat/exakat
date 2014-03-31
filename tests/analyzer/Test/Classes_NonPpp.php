<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Classes_NonPpp extends Analyzer {
    /* 5 methods */

    public function testClasses_NonPpp01()  { $this->generic_test('Classes_NonPpp.01'); }
    public function testClasses_NonPpp02()  { $this->generic_test('Classes_NonPpp.02'); }
    public function testClasses_NonPpp03()  { $this->generic_test('Classes_NonPpp.03'); }
    public function testClasses_NonPpp04()  { $this->generic_test('Classes_NonPpp.04'); }
    public function testClasses_NonPpp05()  { $this->generic_test('Classes_NonPpp.05'); }
}
?>