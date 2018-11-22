<?php

namespace Test\Cakephp;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Cakephp25 extends Analyzer {
    /* 1 methods */

    public function testCakephp_Cakephp2501()  { $this->generic_test('Cakephp/Cakephp25.01'); }
}
?>