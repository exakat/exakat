<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoGetClassNull extends Analyzer {
    /* 1 methods */

    public function testStructures_NoGetClassNull01()  { $this->generic_test('Structures/NoGetClassNull.01'); }
}
?>