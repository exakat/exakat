<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CommonAlternatives extends Analyzer {
    /* 2 methods */

    public function testStructures_CommonAlternatives01()  { $this->generic_test('Structures/CommonAlternatives.01'); }
    public function testStructures_CommonAlternatives02()  { $this->generic_test('Structures/CommonAlternatives.02'); }
}
?>