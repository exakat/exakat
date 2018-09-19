<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class RepeatedRegex extends Analyzer {
    /* 1 methods */

    public function testStructures_RepeatedRegex01()  { $this->generic_test('Structures/RepeatedRegex.01'); }
}
?>