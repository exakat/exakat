<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class OrDie extends Analyzer {
    /* 3 methods */

    public function testStructures_OrDie01()  { $this->generic_test('Structures_OrDie.01'); }
    public function testStructures_OrDie02()  { $this->generic_test('Structures_OrDie.02'); }
    public function testStructures_OrDie03()  { $this->generic_test('Structures_OrDie.03'); }
}
?>