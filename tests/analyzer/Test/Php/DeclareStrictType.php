<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_DeclareStrictType extends Analyzer {
    /* 5 methods */

    public function testPhp_DeclareStrictType01()  { $this->generic_test('Php/DeclareStrictType.01'); }
    public function testPhp_DeclareStrictType02()  { $this->generic_test('Php/DeclareStrictType.02'); }
    public function testPhp_DeclareStrictType03()  { $this->generic_test('Php/DeclareStrictType.03'); }
    public function testPhp_DeclareStrictType04()  { $this->generic_test('Php/DeclareStrictType.04'); }
    public function testPhp_DeclareStrictType05()  { $this->generic_test('Php/DeclareStrictType.05'); }
}
?>