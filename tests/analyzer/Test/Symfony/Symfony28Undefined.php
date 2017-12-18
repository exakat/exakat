<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Symfony_Symfony28Undefined extends Analyzer {
    /* 1 methods */

    public function testSymfony_Symfony28Undefined01()  { $this->generic_test('Symfony/Symfony28Undefined.01'); }
}
?>