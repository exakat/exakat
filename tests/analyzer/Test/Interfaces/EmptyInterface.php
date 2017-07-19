<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Interfaces_EmptyInterface extends Analyzer {
    /* 3 methods */

    public function testInterfaces_EmptyInterface01()  { $this->generic_test('Interfaces_EmptyInterface.01'); }
    public function testInterfaces_EmptyInterface02()  { $this->generic_test('Interfaces/EmptyInterface.02'); }
    public function testInterfaces_EmptyInterface03()  { $this->generic_test('Interfaces/EmptyInterface.03'); }
}
?>