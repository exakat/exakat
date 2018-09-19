<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class AlternativeConsistenceByFile extends Analyzer {
    /* 4 methods */

    public function testStructures_AlternativeConsistenceByFile01()  { $this->generic_test('Structures/AlternativeConsistenceByFile.01'); }
    public function testStructures_AlternativeConsistenceByFile02()  { $this->generic_test('Structures/AlternativeConsistenceByFile.02'); }
    public function testStructures_AlternativeConsistenceByFile03()  { $this->generic_test('Structures/AlternativeConsistenceByFile.03'); }
    public function testStructures_AlternativeConsistenceByFile04()  { $this->generic_test('Structures/AlternativeConsistenceByFile.04'); }
}
?>