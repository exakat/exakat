<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Structures_NotNot extends Analyzer {
    /* 3 methods */

    public function testStructures_NotNot01()  { $this->generic_test('Structures_NotNot.01'); }
    public function testStructures_NotNot02()  { $this->generic_test('Structures_NotNot.02'); }
    public function testStructures_NotNot03()  { $this->generic_test('Structures_NotNot.03'); }
}
?>