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

class WpdbBestUsage extends Analyzer {
    public function analyze() {
        $ignoreCommands = array('SHOW TABLES', 'RENAME TABLE', 'ALTER TABLE', 'CREATE TABLE', 'DROP TABLE', 'DESC', 'TRUNCATE');
        $ignoreCommandsRegex = implode('|', $ignoreCommands);

        // $wpdb->get_var("select ".$wpdb->prefix."table")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs(array('get_var', 'get_col', 'get_results', 'get_row', 'query', 'prepare', 'query'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             // If it's a property, we accept $wpdb
             ->raw('where( __.out("CONCAT").hasLabel("Member").out("OBJECT").has("code", "\$wpdb") )')
             // Some queries won't accept prepared statements
             ->raw('where( __.out("CONCAT").hasLabel("String").has("rank", 0).filter{(it.get().value("noDelimiter") =~ "^('.$ignoreCommandsRegex.') ").getCount() == 0} )');
        $this->prepareQuery();

        // $wpdb->get_var("select {$wpdb->prefix}table")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs(array('get_var', 'get_col', 'get_results', 'get_row', 'query', 'prepare', 'query'))
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIs('T_QUOTE')
             ->hasOut('CONCAT')
             // If it's a property, we accept $wpdb
             ->raw('not( where( __.out("CONCAT").hasLabel("Member").out("OBJECT").has("code", "\$wpdb") ) )')
             // Some queries won't accept prepared statements
             ->raw('where( __.out("CONCAT").hasLabel("String").has("rank", 0).filter{(it.get().value("noDelimiter") =~ "^('.$ignoreCommandsRegex.') ").getCount() == 0} )');
        $this->prepareQuery();
    }
}

?>
