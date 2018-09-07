<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_IncompatibleSignature extends Analyzer {
    /* 5 methods */

    public function testClasses_IncompatibleSignature01()  { $this->generic_test('Classes/IncompatibleSignature.01'); }
    public function testClasses_IncompatibleSignature02()  { $this->generic_test('Classes/IncompatibleSignature.02'); }
    public function testClasses_IncompatibleSignature03()  { $this->generic_test('Classes/IncompatibleSignature.03'); }
    public function testClasses_IncompatibleSignature04()  { $this->generic_test('Classes/IncompatibleSignature.04'); }
    public function testClasses_IncompatibleSignature05()  { $this->generic_test('Classes/IncompatibleSignature.05'); }
}
?>