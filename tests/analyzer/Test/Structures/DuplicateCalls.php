<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_DuplicateCalls extends Analyzer {
    /* 3 methods */

    public function testStructures_DuplicateCalls01()  { $this->generic_test('Structures_DuplicateCalls.01'); }
    public function testStructures_DuplicateCalls02()  { $this->generic_test('Structures_DuplicateCalls.02'); }
    public function testStructures_DuplicateCalls03()  { $this->generic_test('Structures/DuplicateCalls.03'); }
}
?>