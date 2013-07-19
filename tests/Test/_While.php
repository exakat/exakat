<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _While extends Tokenizeur {
    /* 6 methods */

    public function test_While01()  { $this->generic_test('_While.01'); }
    public function test_While02()  { $this->generic_test('_While.02'); }
    public function test_While03()  { $this->generic_test('_While.03'); }
    public function test_While04()  { $this->generic_test('_While.04'); }
    public function test_While05()  { $this->generic_test('_While.05'); }
    public function test_While06()  { $this->generic_test('_While.06'); }
}
?>