<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_TernaryInConcat extends Analyzer {
    /* 2 methods */

    public function testStructures_TernaryInConcat01()  { $this->generic_test('Structures/TernaryInConcat.01'); }
    public function testStructures_TernaryInConcat02()  { $this->generic_test('Structures/TernaryInConcat.02'); }
}
?>