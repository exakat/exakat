<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class RandomWithoutTry extends Analyzer {
    /* 1 methods */

    public function testStructures_RandomWithoutTry01()  { $this->generic_test('Structures/RandomWithoutTry.01'); }
}
?>