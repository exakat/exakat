<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Structures_PhpinfoUsage extends Analyzer {
    /* 1 methods */

    public function testStructures_PhpinfoUsage01()  { $this->generic_test('Structures_PhpinfoUsage.01'); }
}
?>