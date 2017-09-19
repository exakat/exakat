<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_CouldBePrivateConstante extends Analyzer {
    /* 3 methods */

    public function testClasses_CouldBePrivateConstante01()  { $this->generic_test('Classes/CouldBePrivateConstante.01'); }
    public function testClasses_CouldBePrivateConstante02()  { $this->generic_test('Classes/CouldBePrivateConstante.02'); }
    public function testClasses_CouldBePrivateConstante03()  { $this->generic_test('Classes/CouldBePrivateConstante.03'); }
}
?>