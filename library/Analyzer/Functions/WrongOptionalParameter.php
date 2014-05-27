<?php

namespace Analyzer\Functions;

use Analyzer;

class WrongOptionalParameter extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs("Function")
             ->raw("filter{ has_default=false; it.out('ARGUMENTS').out('ARGUMENT').aggregate().sort{it.order}._().filter{
             if (it.out('RIGHT').any()) { 
                has_default = true; 
                false; 
            } else if (it.out('VARIABLE').out('RIGHT').any()) { 
                has_default = true; 
                false; 
            } else { 
                has_default; 
            }}.any()}");
    }
}

?>