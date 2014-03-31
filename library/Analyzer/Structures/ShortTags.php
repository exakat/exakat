<?php

namespace Analyzer\Structures;

use Analyzer;

class ShortTags extends Analyzer\Analyzer {
    protected $severity  = \Analyzer\Analyzer::S_MINOR;
    protected $timeToFix = \Analyzer\Analyzer::T_QUICK;

    protected $phpconfiguration = array("short_open_tags" => true);

    public function analyze() {
        $this->atomIs("Phpcode")
             ->code(array('<?', '<script language="php">', '<%=', '<%'), true);
        $this->prepareQuery();
    }
}

?>