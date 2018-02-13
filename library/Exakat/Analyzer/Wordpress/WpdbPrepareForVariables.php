<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class WpdbPrepareForVariables extends Analyzer {
    public function analyze() {
        $methods = array('get_var', 'get_results', 'get_row', 'get_col');
        
        $wpdb = $this->dictCode->translate(array('$wpdb'));
        
        if (empty($wpdb)) {
            return;
        }

        // $wpdb->get_results("insert into table values (1,$variable,3)")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('OBJECT')
             ->_as('results')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs($methods, self::TRANSLATE, self::CASE_INSENSITIVE)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('String', 'Heredoc'))
             ->outIs('CONCAT')
             ->atomIs(array('Variable', 'Array', 'Member'))
             // If it's a property, we accept $wpdb
             ->raw('not( __.where( __.out("OBJECT").has("code", '.$wpdb[0].') ) )')
             ->back('results');
        $this->prepareQuery();

        // $wpdb->get_results("insert into table values (1,".$variable.",3)")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('OBJECT')
             ->_as('results')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs($methods, self::TRANSLATE, self::CASE_INSENSITIVE)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             ->outIs('CONCAT')
             ->atomIs(array('Variable', 'Array', 'Member'))
             // If it's a property, we accept $wpdb
             ->raw('not( __.where( __.out("OBJECT").has("code", '.$wpdb[0].') ) )')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
