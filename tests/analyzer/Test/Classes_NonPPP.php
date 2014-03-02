<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Classes_NonPPP extends Analyzer {
    /* 5 methods */

    public function testClasses_NonPPP01()  { $this->generic_test('Classes_NonPPP.01'); }
    public function testClasses_NonPPP02()  { $this->generic_test('Classes_NonPPP.02'); }
    public function testClasses_NonPPP03()  { $this->generic_test('Classes_NonPPP.03'); }
    public function testClasses_NonPPP04()  { $this->generic_test('Classes_NonPPP.04'); }
    public function testClasses_NonPPP05()  { $this->generic_test('Classes_NonPPP.05'); }
}
?>