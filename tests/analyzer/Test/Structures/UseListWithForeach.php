<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_UseListWithForeach extends Analyzer {
    /* 4 methods */

    public function testStructures_UseListWithForeach01()  { $this->generic_test('Structures/UseListWithForeach.01'); }
    public function testStructures_UseListWithForeach02()  { $this->generic_test('Structures/UseListWithForeach.02'); }
    public function testStructures_UseListWithForeach03()  { $this->generic_test('Structures/UseListWithForeach.03'); }
    public function testStructures_UseListWithForeach04()  { $this->generic_test('Structures/UseListWithForeach.04'); }
}
?>