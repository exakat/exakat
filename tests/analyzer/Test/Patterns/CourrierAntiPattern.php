<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Patterns_CourrierAntiPattern extends Analyzer {
    /* 1 methods */

    public function testPatterns_CourrierAntiPattern01()  { $this->generic_test('Patterns/CourrierAntiPattern.01'); }
}
?>