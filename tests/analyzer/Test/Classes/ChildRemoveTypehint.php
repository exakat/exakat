<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ChildRemoveTypehint extends Analyzer {
    /* 3 methods */

    public function testClasses_ChildRemoveTypehint01()  { $this->generic_test('Classes/ChildRemoveTypehint.01'); }
    public function testClasses_ChildRemoveTypehint02()  { $this->generic_test('Classes/ChildRemoveTypehint.02'); }
    public function testClasses_ChildRemoveTypehint03()  { $this->generic_test('Classes/ChildRemoveTypehint.03'); }
}
?>