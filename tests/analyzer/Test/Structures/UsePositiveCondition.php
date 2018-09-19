<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UsePositiveCondition extends Analyzer {
    /* 3 methods */

    public function testStructures_UsePositiveCondition01()  { $this->generic_test('Structures/UsePositiveCondition.01'); }
    public function testStructures_UsePositiveCondition02()  { $this->generic_test('Structures/UsePositiveCondition.02'); }
    public function testStructures_UsePositiveCondition03()  { $this->generic_test('Structures/UsePositiveCondition.03'); }
}
?>