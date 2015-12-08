<?php

namespace Analyzer\Variables;

use Analyzer;

class RealVariables extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('Variable')
             ->hasNoIn(array('DEFINE', 'PROPERTY', 'GLOBAL'))
             ->filter(' it.in("LEFT").in("DEFINE").any() == false') // static $a = 2;
             ->filter(' it.in("LEFT").in("ARGUMENT").any() == false') // $a = 2 in definition
             ->filter(' it.in("LEFT").in("VARIABLE").in("ARGUMENT").any() == false') // $a = 2 in definition with typehint
             ->filter(' it.in("VARIABLE").in("ARGUMENT").any() == false') // $a = 2 in definition with typehint
             ->back('first');
        $this->prepareQuery();
    }
}

?>
