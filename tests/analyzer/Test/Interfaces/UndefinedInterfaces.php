<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Interfaces_UndefinedInterfaces extends Analyzer {
    /* 7 methods */

    public function testInterfaces_UndefinedInterfaces01()  { $this->generic_test('Interfaces_UndefinedInterfaces.01'); }
    public function testInterfaces_UndefinedInterfaces02()  { $this->generic_test('Interfaces_UndefinedInterfaces.02'); }
    public function testInterfaces_UndefinedInterfaces03()  { $this->generic_test('Interfaces_UndefinedInterfaces.03'); }
    public function testInterfaces_UndefinedInterfaces04()  { $this->generic_test('Interfaces_UndefinedInterfaces.04'); }
    public function testInterfaces_UndefinedInterfaces05()  { $this->generic_test('Interfaces/UndefinedInterfaces.05'); }
    public function testInterfaces_UndefinedInterfaces06()  { $this->generic_test('Interfaces/UndefinedInterfaces.06'); }
    public function testInterfaces_UndefinedInterfaces07()  { $this->generic_test('Interfaces/UndefinedInterfaces.07'); }
}
?>