<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CantUseReturnValueInWriteContext extends Analyzer {
    /* 1 methods */

    public function testPhp_CantUseReturnValueInWriteContext01()  { $this->generic_test('Php/CantUseReturnValueInWriteContext.01'); }
}
?>