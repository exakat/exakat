<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoDirectUsage extends Analyzer {
    /* 4 methods */

    public function testStructures_NoDirectUsage01()  { $this->generic_test('Structures_NoDirectUsage.01'); }
    public function testStructures_NoDirectUsage02()  { $this->generic_test('Structures_NoDirectUsage.02'); }
    public function testStructures_NoDirectUsage03()  { $this->generic_test('Structures_NoDirectUsage.03'); }
    public function testStructures_NoDirectUsage04()  { $this->generic_test('Structures/NoDirectUsage.04'); }
}
?>