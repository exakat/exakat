<?php

namespace Analyzer\Spip;

use Analyzer;

class LectureCookie extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Variables\\IsRead',
                     'Analyzer\\Arrays\\IsRead',
                     );
    }
    
    public function analyze() {
        // $_COOKIE just read
        $this->atomIs('Variable')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->code('$_COOKIE')
             ->analyzerIs('Analyzer\\Variables\\IsRead')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_COOKIE[] just read
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->code('$_COOKIE')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_COOKIE['a'][] just read (2 levels)
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code('$_COOKIE')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();

        // $_COOKIE['a']['b'][] just read (3 levels)
        $this->atomIs('Array')
             ->hasNoIn('VARIABLE') // exclude arrays
             ->analyzerIs('Analyzer\\Arrays\\IsRead')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->outIs('VARIABLE')
             ->code('$_COOKIE')
             ->raw('filter{ it.in.loop(1){true}{it.object.atom == "Function"}.out("NAME").has("code", "recuperer_cookies_spip").any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
