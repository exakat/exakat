<?php

namespace Analyzer\Php;

use Analyzer;

class Php70RemovedDirective extends Analyzer\Common\UsedDirective {
    protected $phpVersion = '7.0+';
    
    public function analyze() {
        $this->directives = array('always_populate_raw_post_data',
                                  'asp_tags',
                                  'xsl.security_prefs');

        parent::analyze();
    }
}

?>
