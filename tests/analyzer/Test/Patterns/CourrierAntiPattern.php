<?php

namespace Test\Patterns;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CourrierAntiPattern extends Analyzer {
    /* 1 methods */

    public function testPatterns_CourrierAntiPattern01()  { $this->generic_test('Patterns/CourrierAntiPattern.01'); }
}
?>