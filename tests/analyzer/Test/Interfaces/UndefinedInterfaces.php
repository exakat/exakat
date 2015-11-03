<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Interfaces_UndefinedInterfaces extends Analyzer {
    /* 4 methods */

    public function testInterfaces_UndefinedInterfaces01()  { $this->generic_test('Interfaces_UndefinedInterfaces.01'); }
    public function testInterfaces_UndefinedInterfaces02()  { $this->generic_test('Interfaces_UndefinedInterfaces.02'); }
    public function testInterfaces_UndefinedInterfaces03()  { $this->generic_test('Interfaces_UndefinedInterfaces.03'); }
    public function testInterfaces_UndefinedInterfaces04()  { $this->generic_test('Interfaces_UndefinedInterfaces.04'); }
}
?>