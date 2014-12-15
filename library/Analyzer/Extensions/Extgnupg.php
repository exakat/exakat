<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extgnupg extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'gnupg.ini';
        
        parent::analyze();
    }
}

?>
