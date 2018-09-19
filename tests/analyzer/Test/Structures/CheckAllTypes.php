<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CheckAllTypes extends Analyzer {
    /* 2 methods */

    public function testStructures_CheckAllTypes01()  { $this->generic_test('Structures/CheckAllTypes.01'); }
    public function testStructures_CheckAllTypes02()  { $this->generic_test('Structures/CheckAllTypes.02'); }
}
?>