<?php

namespace Analyzer\Spip;

use Analyzer;

class LectureGPR extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsRead',
                     'Analyzer\\Arrays\\IsRead');
    }
    
    public function analyze() {
        $gpr = array('$_GET', '$_POST', '$_REQUEST');

        // $_GPR just read
        $this->atomIs('Variable')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->code($gpr)
             ->analyzerIs('Analyzer\\Variables\\IsRead')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "process_gpr").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_GPR[] just read
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->code($gpr)
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "process_gpr").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_GPR['a'][] just read (2 levels)
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code($gpr)
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "process_gpr").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_GPR['a']['b'][] just read (3 levels)
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code($gpr)
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "process_gpr").any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>