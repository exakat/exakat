<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class InvalidPackFormat extends Analyzer {
    /* 2 methods */

    public function testStructures_InvalidPackFormat01()  { $this->generic_test('Structures/InvalidPackFormat.01'); }
    public function testStructures_InvalidPackFormat02()  { $this->generic_test('Structures/InvalidPackFormat.02'); }
}
?>