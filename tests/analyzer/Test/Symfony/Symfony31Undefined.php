<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Symfony_Symfony31Undefined extends Analyzer {
    /* 1 methods */

    public function testSymfony_Symfony31Undefined01()  { $this->generic_test('Symfony/Symfony31Undefined.01'); }
}
?>