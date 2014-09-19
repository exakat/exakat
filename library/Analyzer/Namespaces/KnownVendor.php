<?php

namespace Analyzer\Namespaces;

use Analyzer;

class KnownVendor extends Analyzer\Analyzer {
    public function analyze() {
        // @todo move this to a SQL table! 
        $knownVendors = $this->getVendors();
        
        $this->atomIs("Nsname")
             ->hasIn('USE') // no fullnspath for namespace 
             ->outIs('SUBNAME')
             ->is('order', 0)
             ->code($knownVendors)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("Functioncall")
             ->hasIn('NEW') // special case for instantiation
             ->outIs('SUBNAME')
             ->is('order', 0)
             ->code($knownVendors)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs("As")
             ->hasIn('USE') 
             ->outIs('SUBNAME')
             ->is('order', 0)
             ->code($knownVendors)
             ->back('first');
        $this->prepareQuery();
    }
}

?>