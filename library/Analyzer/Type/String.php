<?php

namespace Analyzer\Type;

use Analyzer;

class String extends Analyzer\Analyzer {

    function analyze() {
        $this->atomIs(array('String', 'HereDoc', 'NowDoc'))
             ->tokenIsNot('T_QUOTE');
    }

    function toCountedArray() {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = "m = [:]; g.idx('analyzers')[['analyzer':'".$analyzer."']].out.groupCount(m){it.fullcode.replaceFirst(\"^['\\\"]?(.*?)['\\\"]?\\\$\", \"\\\$1\")}.cap"; 
        $vertices = query($this->client, $queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices[0][0] as $k => $v) {
                $report[$k] = $v;
            }   
        } 
        
        return $report;
    }

}

?>