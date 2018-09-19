<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class DifferencePreference extends Analyzer {
    /* 3 methods */

    public function testStructures_DifferencePreference01()  { $this->generic_test('Structures/DifferencePreference.01'); }
    public function testStructures_DifferencePreference02()  { $this->generic_test('Structures/DifferencePreference.02'); }
    public function testStructures_DifferencePreference03()  { $this->generic_test('Structures/DifferencePreference.03'); }
}
?>