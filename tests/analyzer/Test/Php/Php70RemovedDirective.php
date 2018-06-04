<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_Php70RemovedDirective extends Analyzer {
    /* 2 methods */

    public function testPhp_Php70RemovedDirective01()  { $this->generic_test('Php/Php70RemovedDirective.01'); }
    public function testPhp_Php70RemovedDirective02()  { $this->generic_test('Php/Php70RemovedDirective.02'); }
}
?>