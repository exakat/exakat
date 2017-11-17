<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UseThis extends Analyzer {
    /* 5 methods */

    public function testClasses_UseThis01()  { $this->generic_test('Classes/UseThis.01'); }
    public function testClasses_UseThis02()  { $this->generic_test('Classes/UseThis.02'); }
    public function testClasses_UseThis03()  { $this->generic_test('Classes/UseThis.03'); }
    public function testClasses_UseThis04()  { $this->generic_test('Classes/UseThis.04'); }
    public function testClasses_UseThis05()  { $this->generic_test('Classes/UseThis.05'); }
}
?>