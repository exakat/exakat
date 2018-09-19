<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Pcre extends Analyzer {
    /* 3 methods */

    public function testType_Pcre01()  { $this->generic_test('Type_Pcre.01'); }
    public function testType_Pcre02()  { $this->generic_test('Type_Pcre.02'); }
    public function testType_Pcre03()  { $this->generic_test('Type/Pcre.03'); }
}
?>