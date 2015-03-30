<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Security_ShouldUsePreparedStatement extends Analyzer {
    /* 2 methods */

    public function testSecurity_ShouldUsePreparedStatement01()  { $this->generic_test('Security_ShouldUsePreparedStatement.01'); }
    public function testSecurity_ShouldUsePreparedStatement02()  { $this->generic_test('Security_ShouldUsePreparedStatement.02'); }
}
?>