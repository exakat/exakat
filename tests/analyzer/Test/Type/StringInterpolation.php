<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class StringInterpolation extends Analyzer {
    /* 2 methods */

    public function testType_StringInterpolation01()  { $this->generic_test('Type_StringInterpolation.01'); }
    public function testType_StringInterpolation02()  { $this->generic_test('Type/StringInterpolation.02'); }
}
?>