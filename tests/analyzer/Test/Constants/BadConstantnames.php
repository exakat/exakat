<?php

namespace Test\Constants;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class BadConstantnames extends Analyzer {
    /* 4 methods */

    public function testConstants_BadConstantnames01()  { $this->generic_test('Constants_BadConstantnames.01'); }
    public function testConstants_BadConstantnames02()  { $this->generic_test('Constants_BadConstantnames.02'); }
    public function testConstants_BadConstantnames03()  { $this->generic_test('Constants_BadConstantnames.03'); }
    public function testConstants_BadConstantnames04()  { $this->generic_test('Constants/BadConstantnames.04'); }
}
?>