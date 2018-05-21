<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_TypehintedReferences extends Analyzer {
    /* 3 methods */

    public function testFunctions_TypehintedReferences01()  { $this->generic_test('Functions/TypehintedReferences.01'); }
    public function testFunctions_TypehintedReferences02()  { $this->generic_test('Functions/TypehintedReferences.02'); }
    public function testFunctions_TypehintedReferences03()  { $this->generic_test('Functions/TypehintedReferences.03'); }
}
?>