<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_CallbackNeedsReturn extends Analyzer {
    /* 3 methods */

    public function testFunctions_CallbackNeedsReturn01()  { $this->generic_test('Functions/CallbackNeedsReturn.01'); }
    public function testFunctions_CallbackNeedsReturn02()  { $this->generic_test('Functions/CallbackNeedsReturn.02'); }
    public function testFunctions_CallbackNeedsReturn03()  { $this->generic_test('Functions/CallbackNeedsReturn.03'); }
}
?>