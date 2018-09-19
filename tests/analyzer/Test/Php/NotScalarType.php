<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NotScalarType extends Analyzer {
    /* 3 methods */

    public function testPhp_NotScalarType01()  { $this->generic_test('Php/NotScalarType.01'); }
    public function testPhp_NotScalarType02()  { $this->generic_test('Php/NotScalarType.02'); }
    public function testPhp_NotScalarType03()  { $this->generic_test('Php/NotScalarType.03'); }
}
?>