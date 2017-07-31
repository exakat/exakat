<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_CouldBeProtectedProperty extends Analyzer {
    /* 3 methods */

    public function testClasses_CouldBeProtectedProperty01()  { $this->generic_test('Classes/CouldBeProtectedProperty.01'); }
    public function testClasses_CouldBeProtectedProperty02()  { $this->generic_test('Classes/CouldBeProtectedProperty.02'); }
    public function testClasses_CouldBeProtectedProperty03()  { $this->generic_test('Classes/CouldBeProtectedProperty.03'); }
}
?>