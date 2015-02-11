<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UnresolvedInstanceof extends Analyzer {
    /* 3 methods */

    public function testClasses_UnresolvedInstanceof01()  { $this->generic_test('Classes_UnresolvedInstanceof.01'); }
    public function testClasses_UnresolvedInstanceof02()  { $this->generic_test('Classes_UnresolvedInstanceof.02'); }
    public function testClasses_UnresolvedInstanceof03()  { $this->generic_test('Classes_UnresolvedInstanceof.03'); }
}
?>