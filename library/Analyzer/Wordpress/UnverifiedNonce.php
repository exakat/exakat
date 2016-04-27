<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Analyzer\Wordpress;

use Analyzer;

class UnverifiedNonce extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Wordpress/NonceCreation');
    }
    
    public function analyze() {
        // Search for wp_verify_nonce usage
        $list = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]].has("atom", "Functioncall")
    .as("first").filter{ it.inE.filter{ it.label in ["METHOD", "NEW"]}.any() == false}
    .filter{it.token in ["T_STRING", "T_NS_SEPARATOR"] }
    .filter{it.fullnspath.toLowerCase() == "\\\\wp_verify_nonce"}
    .out("ARGUMENTS").out("ARGUMENT")
    .filter{ it.rank in 0;}
    .has("atom", "Array")
    .filter{ it.out("VARIABLE").filter{ it.code in ["\\\$_GET", "\\\$_POST", "\\\$_REQUEST"]}.any()}
    .out("INDEX")
    .has("atom", "String")
    .noDelimiter
    .unique()
GREMLIN
);

        // Search for wp_verify_nonce usage
        $list2 = $this->query(<<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]].has("atom", "Functioncall")
    .as("first").filter{ it.inE.filter{ it.label in ["METHOD", "NEW"]}.any() == false}
    .filter{it.token in ["T_STRING", "T_NS_SEPARATOR"] }
    .filter{it.fullnspath.toLowerCase() == "\\\\check_ajax_referer"}
    .out("ARGUMENTS").out("ARGUMENT")
    .filter{ it.rank in 0;}
    .has("atom", "String")
    .has("atom", "String")
    .noDelimiter
    .unique()
GREMLIN
);
        $list = array_merge($list, $list2);
        
        $this->analyzerIs('Wordpress/NonceCreation')
             ->noDelimiterIsNot($list);
        $this->prepareQuery();
    }
}

?>
