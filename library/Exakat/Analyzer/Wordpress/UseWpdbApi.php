<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class UseWpdbApi extends Analyzer {
    public function analyze() {
        $crud = array('INSERT', 'UPDATE', 'DELETE', 'REPLACE');
        $crudAlternative = implode('|', $crud);

        // $wpdb->query('delete from sometable')
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->_as('results')
             ->outIs('METHOD')
             ->codeIs(array('query', 'prepare'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             // If it's a property, we accept $wpdb
             ->hasNoOut('CONCAT')
             ->regexIs('noDelimiter', '^(?i)('.$crudAlternative.')')
             ->back('results');
        $this->prepareQuery();
        
        // $wpdb->query("delete from ".$wpdb->prefix."table")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->_as('results')
             ->outIs('METHOD')
             ->codeIs(array('query', 'prepare'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             // If it's a property, we accept $wpdb
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             ->regexIs('noDelimiter', '^(?i)('.$crudAlternative.')')
             ->back('results');
        $this->prepareQuery();

        // $wpdb->query("delete from $wpdb->prefix")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->_as('results')
             ->outIs('METHOD')
             ->codeIs(array('query', 'prepare'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIs('T_QUOTE')
             ->outWithRank('CONCAT', 0)
             ->atomIs('String')
             // If it's a property, we accept $wpdb
             ->regexIs('noDelimiter', '^(?i)('.$crudAlternative.')')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
