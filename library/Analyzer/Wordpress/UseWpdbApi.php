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

class UseWpdbApi extends Analyzer\Analyzer {
    public function analyze() {
        $crud = array('INSERT', 'UPDATE', 'DELETE', 'REPLACE');
        $crudAlternative = join('|', $crud);
        
        // $wpdb->query("delete from ".$wpdb->prefix."table") 
        $this->atomIs('Variable')
             ->codeIs('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->_as('results')
             ->outIs('METHOD')
             ->codeIs(array('query', 'prepare'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('Concatenation')
             // If it's a property, we accept $wpdb
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             ->regexIs('noDelimiter', '^(?i)('.$crudAlternative.')')
//             ->filter('it.out("CONCAT").has("atom", "String").has("rank", 0).filter{ (it.noDelimiter =~ ).getCount() > 0}.any()')
             ->back('results');
        $this->prepareQuery();

        // $wpdb->query("delete from $wpdb->prefixtable") 
        $this->atomIs('Variable')
             ->codeIs('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->_as('results')
             ->outIs('METHOD')
             ->codeIs(array('query', 'prepare'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->tokenIs('T_QUOTE')
             ->outIs('CONTAINS')
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             // If it's a property, we accept $wpdb
             ->regexIs('noDelimiter', '^(?i)('.$crudAlternative.')')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
