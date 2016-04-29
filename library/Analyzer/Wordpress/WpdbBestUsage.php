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

class WpdbBestUsage extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $ignoreCommands = array('SHOW TABLES', 'RENAME TABLE', 'ALTER TABLE', 'CREATE TABLE', 'DROP TABLE', 'DESC');
        $ignoreCommandsRegex = join('|', $ignoreCommands);

        // $wpdb->get_var("select ".$wpdb->prefix."table") 
        $this->atomIs('Variable')
             ->code('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->code(array('get_var', 'get_col', 'get_results', 'get_row', 'query', 'prepare', 'query'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('Concatenation')
             // If it's a property, we accept $wpdb
             ->filter('it.out("CONCAT").has("atom", "Property").filter{ it.out("OBJECT").has("code", "\$wpdb").any()}.any()')
             // Some queries won't accept prepared statements
             ->filter('it.out("CONCAT").has("rank", 0).has("atom", "String").filter{(it.noDelimiter =~ "^(SHOW TABLES|DESC) ").getCount() == 0}.any()');
        $this->prepareQuery();

        // $wpdb->get_var("select {$wpdb->prefix}table") 
        $this->atomIs('Variable')
             ->code('$wpdb')
             ->inIs('OBJECT')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->code(array('get_var', 'get_col', 'get_results', 'get_row', 'query', 'prepare', 'query'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->tokenIs('T_QUOTE')
             ->outIs('CONTAINS')
             // If it's a property, we accept $wpdb
             ->filter('it.out("CONCAT").has("atom", "Property").filter{ it.out("OBJECT").has("code", "\$wpdb").any()}.any() == false')
             // Some queries won't accept prepared statements
             ->filter('it.out("CONCAT").has("rank", 0).has("atom", "String").filter{(it.noDelimiter =~ "^ ?(?i)('.$ignoreCommandsRegex.') ").getCount() == 0}.any()');
        $this->prepareQuery();
    }
}

?>
