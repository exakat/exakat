<?php

namespace Analyzer\Extensions;

use Analyzer;

class Extcyrus extends Analyzer\Common\Extension {

    public function analyze() {
        $this->source = 'cyrus.ini';
        
        parent::analyze();
    }
}

?>
