<?php

namespace Analyzer\Php;

use Analyzer;

class InconsistantClosingTag extends Analyzer\Analyzer {

    public function analyze() {
        
        $this->atomIs("Phpcode")
             ->is('root', 'true')
             ->groupFilter("if (it.closing_tag == 'true') { x2 = 'closed'; } else { x2 = 'not_closed'; } ", 10 / 100);
    }
}

?>