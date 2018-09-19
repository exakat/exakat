<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NotNot extends Analyzer {
    /* 4 methods */

    public function testStructures_NotNot01()  { $this->generic_test('Structures_NotNot.01'); }
    public function testStructures_NotNot02()  { $this->generic_test('Structures_NotNot.02'); }
    public function testStructures_NotNot03()  { $this->generic_test('Structures_NotNot.03'); }
    public function testStructures_NotNot04()  { $this->generic_test('Structures/NotNot.04'); }
}
?>