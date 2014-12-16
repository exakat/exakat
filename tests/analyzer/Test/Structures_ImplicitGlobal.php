<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ImplicitGlobal extends Analyzer {
    /* 2 methods */

    public function testStructures_ImplicitGlobal01()  { $this->generic_test('Structures_ImplicitGlobal.01'); }
    public function testStructures_ImplicitGlobal02()  { $this->generic_test('Structures_ImplicitGlobal.02'); }
}
?>