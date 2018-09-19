<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Htmlentitiescall extends Analyzer {
    /* 3 methods */

    public function testStructures_Htmlentitiescall01()  { $this->generic_test('Structures_Htmlentitiescall.01'); }
    public function testStructures_Htmlentitiescall02()  { $this->generic_test('Structures_Htmlentitiescall.02'); }
    public function testStructures_Htmlentitiescall03()  { $this->generic_test('Structures_Htmlentitiescall.03'); }
}
?>