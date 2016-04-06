<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_IdenticalConditions extends Analyzer {
    /* 3 methods */

    public function testStructures_IdenticalConditions01()  { $this->generic_test('Structures/IdenticalConditions.01'); }
    public function testStructures_IdenticalConditions02()  { $this->generic_test('Structures/IdenticalConditions.02'); }
    public function testStructures_IdenticalConditions03()  { $this->generic_test('Structures/IdenticalConditions.03'); }
}
?>