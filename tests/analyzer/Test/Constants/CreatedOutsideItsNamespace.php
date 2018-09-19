<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CreatedOutsideItsNamespace extends Analyzer {
    /* 3 methods */

    public function testConstants_CreatedOutsideItsNamespace01()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.01'); }
    public function testConstants_CreatedOutsideItsNamespace02()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.02'); }
    public function testConstants_CreatedOutsideItsNamespace03()  { $this->generic_test('Constants_CreatedOutsideItsNamespace.03'); }
}
?>