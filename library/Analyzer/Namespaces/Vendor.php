<?php

namespace Analyzer\Namespaces;

use Analyzer;

class Vendor extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Namespaces\\Namespacesnames");
    }
    
    public function analyze() {
        $this->atomIs("Namespace")
             ->regex('fullcode', '^namespace [a-zA-Z0-9_]+\\\\\\\\');
    }

    public function toArray() {
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = "g.idx('analyzers')[['analyzer':'".$analyzer."']].out.fullcode.tokenize(\" \")[1]"; 
        $vertices = query($this->client, $queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = $v[0]->unicode_block;
            }   
        } 
        
        return $report;
    }

    function toCountedArray($load = "it.fullcode") {
        return parent::toCountedArray("it.fullcode.tokenize(\" \\\\\")[1]");
/*        
        $analyzer = str_replace('\\', '\\\\', get_class($this));
        $queryTemplate = "m = [:]; g.idx('analyzers')[['analyzer':'".$analyzer."']].out.groupCount(m){}.cap"; 
        $vertices = query($this->client, $queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices[0][0] as $k => $v) {
                $report[$k] = $v;
            }   
        } 
        
        return $report;*/
    }

}

?>
