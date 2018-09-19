<?php

namespace Test\Type;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');
spl_autoload_register('Autoload::autoload_library');

class Binary extends Analyzer {
    /* 3 methods */

    public function testType_Binary01()  { $this->generic_test('Type_Binary.01'); }
    public function testType_Binary02()  { $this->generic_test('Type_Binary.02'); }
    public function testType_Binary03()  { $this->generic_test('Type/Binary.03'); }
}
?>