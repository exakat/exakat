<?php

namespace Analyzer\Type;

use Analyzer;

class UnicodeBlock extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs(array('String', 'HereDoc', 'NowDoc'));
    }

    public function toArray() {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = "g.idx('analyzers')[['analyzer':'Analyzer\\Type\\UnicodeBlock']].out.hasNot('unicode_block', null)"; 
        $vertices = query($this->client, $queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = $v[0]->unicode_block;
            }   
        } 
        
        return $report;
    }

    function toCountedArray() {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = "m = [:]; g.idx('analyzers')[['analyzer':'".$analyzer."']].out.hasNot('unicode_block', null).groupCount(m){it.unicode_block}.cap"; 
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