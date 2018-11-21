<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Wordpress;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\Dictionary;

class UnverifiedNonce extends Analyzer {
    public function dependsOn() {
        return array('Wordpress/NonceCreation');
    }
    
    public function analyze() {
        $phpVariables = array('$_GET', '$_POST', '$_REQUEST');
        $phpVariables = $this->dictCode->translate($phpVariables);
        $phpVariablesList = makeList($phpVariables, '');
    
        // Search for wp_verify_nonce usage
        $list = $this->query(<<<GREMLIN
g.V().hasLabel("Functioncall").as("first")
     .has("token", within("T_STRING", "T_NS_SEPARATOR") )
     .has("fullnspath", within("\\\\wp_nonce_field", "\\\\wp_verify_nonce") )
     .out("ARGUMENT")
     .has("rank", 0)
     .hasLabel("Array")
     .where( __.out("VARIABLE").has("code", within($phpVariablesList)) )
     .out("INDEX")
     .hasLabel("String")
     .values("noDelimiter")
     .unique()
GREMLIN
                    )->toArray();

        // Search for wp_verify_nonce usage
        $list2 = $this->query(<<<GREMLIN

g.V().hasLabel("Functioncall").as("first")
     .has("token", within("T_STRING", "T_NS_SEPARATOR") )
     .has("fullnspath", within("\\\\wp_nonce_field", "\\\\check_ajax_referer", "\\\\check_admin_referer") )
     .out("ARGUMENT")
     .has("rank", 0)
     .hasLabel("String")
     .values("noDelimiter")
     .unique()
GREMLIN
                    )->toArray();
        $list = array_merge($list, $list2);
        
        if (empty($list)) {
            return;
        }
        
        $this->analyzerIs('Wordpress/NonceCreation')
             ->noDelimiterIsNot($list);
        $this->prepareQuery();
    }
}

?>
