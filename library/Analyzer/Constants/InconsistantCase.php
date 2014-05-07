<?php

namespace Analyzer\Constants;

use Analyzer;

class InconsistantCase extends Analyzer\Analyzer {

    public function analyze() {
        return true;
        
        $this->tokenIs("T_STRING")
             ->raw("filter{it.getProperty('atom') == 'Boolean' || it.getProperty('code').toLowerCase() == 'null'}.transform{ if (it.code.toLowerCase() == it.code) { 'Lowercase'; } else if (it.code.toUpperCase() == it.code) { 'Uppercase'; } else { 'Other'; }}.groupCount()");
    }
}

?>