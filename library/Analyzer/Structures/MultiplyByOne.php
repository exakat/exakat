<?php

namespace Analyzer\Structures;

use Analyzer;

class MultiplyByOne extends Analyzer\Analyzer {
    public function analyze() {
        // $x *= 1;
        $this->atomIs('Assignation')
             ->code(array('*=', '/=', '%=', '**='))
             ->outIs('RIGHT')
             ->code(1)
             ->back('first');
        $this->prepareQuery();

        // $x = $y * 1 
        $this->atomIs('Multiplication')
             ->code('*')
             ->outIs('LEFT')
             ->code('1')
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Multiplication')
             ->outIs('RIGHT')
             ->code('1')
             ->back('first');
        $this->prepareQuery();

        // $x = $y ** 1 
        $this->atomIs('Power')
             ->outIs('RIGHT')
             ->code('1')
             ->back('first');
        $this->prepareQuery();

        // 1 ** $a;
        $this->atomIs('Power')
             ->outIs('LEFT')
             ->code(array('1', '0'))
             ->back('first');
        $this->prepareQuery();
        
        // -0 
        $this->atomIs('Integer')
             ->code('-0');
        $this->prepareQuery();
    }
}

?>
