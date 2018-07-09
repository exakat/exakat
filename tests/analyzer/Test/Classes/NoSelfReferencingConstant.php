<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_NoSelfReferencingConstant extends Analyzer {
    /* 5 methods */

    public function testClasses_NoSelfReferencingConstant01()  { $this->generic_test('Classes_NoSelfReferencingConstant.01'); }
    public function testClasses_NoSelfReferencingConstant02()  { $this->generic_test('Classes_NoSelfReferencingConstant.02'); }
    public function testClasses_NoSelfReferencingConstant03()  { $this->generic_test('Classes/NoSelfReferencingConstant.03'); }
    public function testClasses_NoSelfReferencingConstant04()  { $this->generic_test('Classes/NoSelfReferencingConstant.04'); }
    public function testClasses_NoSelfReferencingConstant05()  { $this->generic_test('Classes/NoSelfReferencingConstant.05'); }
}
?>