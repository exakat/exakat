<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class DefinedParentMP extends Analyzer {
    /* 6 methods */

    public function testClasses_DefinedParentMP01()  { $this->generic_test('Classes/DefinedParentMP.01'); }
    public function testClasses_DefinedParentMP02()  { $this->generic_test('Classes/DefinedParentMP.02'); }
    public function testClasses_DefinedParentMP03()  { $this->generic_test('Classes/DefinedParentMP.03'); }
    public function testClasses_DefinedParentMP04()  { $this->generic_test('Classes/DefinedParentMP.04'); }
    public function testClasses_DefinedParentMP05()  { $this->generic_test('Classes/DefinedParentMP.05'); }
    public function testClasses_DefinedParentMP06()  { $this->generic_test('Classes/DefinedParentMP.06'); }
}
?>