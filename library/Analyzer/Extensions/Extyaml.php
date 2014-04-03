<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extyaml extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'yaml.ini';
        
        parent::analyze();
    }
}

?>