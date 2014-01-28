<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extenchant extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'enchant.ini';
        
        parent::analyze();
    }
}

?>