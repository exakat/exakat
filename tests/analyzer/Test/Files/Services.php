<?php

namespace Test\Files;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Services extends Analyzer {
    /* 1 methods */

    public function testFiles_Services01()  { $this->generic_test('Files/Services.01'); }
}
?>