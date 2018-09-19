<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoListWithString extends Analyzer {
    /* 1 methods */

    public function testPhp_NoListWithString01()  { $this->generic_test('Php/NoListWithString.01'); }
}
?>