<?php

namespace Analyzer\Extensions;

use Analyzer;

class Kdm5 extends Analyzer\Analyzer {

    function analyze() {
        $functions = file(dirname(dirname(dirname(__DIR__))).'/data/kdm5.txt');
        foreach($functions as $i => $f) {
            $functions[$i] = trim($f);
        }

        $this->atomIs("Functioncall")
             ->code($functions);
        $this->prepareQuery();
    }
}

?>