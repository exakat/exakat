<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IdenticalConsecutive extends Analyzer {
    /* 3 methods */

    public function testStructures_IdenticalConsecutive01()  { $this->generic_test('Structures/IdenticalConsecutive.01'); }
    public function testStructures_IdenticalConsecutive02()  { $this->generic_test('Structures/IdenticalConsecutive.02'); }
    public function testStructures_IdenticalConsecutive03()  { $this->generic_test('Structures/IdenticalConsecutive.03'); }
}
?>