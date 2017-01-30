<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php_Php72Deprecation extends Analyzer {
    /* 6 methods */

    public function testPhp_Php72Deprecation01()  { $this->generic_test('Php/Php72Deprecation.01'); }
    public function testPhp_Php72Deprecation02()  { $this->generic_test('Php/Php72Deprecation.02'); }
    public function testPhp_Php72Deprecation03()  { $this->generic_test('Php/Php72Deprecation.03'); }
    public function testPhp_Php72Deprecation04()  { $this->generic_test('Php/Php72Deprecation.04'); }
    public function testPhp_Php72Deprecation05()  { $this->generic_test('Php/Php72Deprecation.05'); }
    public function testPhp_Php72Deprecation06()  { $this->generic_test('Php/Php72Deprecation.06'); }
}
?>