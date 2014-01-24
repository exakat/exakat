<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extctype extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'ctype.ini';
        
        parent::analyze();
    }
}

?>