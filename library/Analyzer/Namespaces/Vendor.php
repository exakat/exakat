<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


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
        $vertices = $this->query($this->client, $queryTemplate);

        $report = array();
        if (count($vertices) > 0) {
            foreach($vertices as $v) {
                $report[] = $v[0]->unicode_block;
            }   
        } 
        
        return $report;
    }

    public function toCountedArray($load = "it.fullcode") {
        return parent::toCountedArray("it.fullcode.tokenize(\" \\\\\")[1]");
    }

}

?>
