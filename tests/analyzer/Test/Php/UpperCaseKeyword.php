<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UpperCaseKeyword extends Analyzer {
    /* 3 methods */

    public function testPhp_UpperCaseKeyword01()  { $this->generic_test('Php/UpperCaseKeyword.01'); }
    public function testPhp_UpperCaseKeyword02()  { $this->generic_test('Php/UpperCaseKeyword.02'); }
    public function testPhp_UpperCaseKeyword03()  { $this->generic_test('Php/UpperCaseKeyword.03'); }
}
?>