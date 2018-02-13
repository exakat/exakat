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

class WpdbPrepareOrNot extends Analyzer {
    public function analyze() {
        $wpdb = $this->dictCode->translate(array('$wpdb'));
        
        if (empty($wpdb)) {
            return;
        }

        // No variable, don't use prepare
        // $wpdb->prepare("insert into table values (1,2,3)")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('OBJECT')
             ->_as('results')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs('prepare')
             ->is('count', 1)
             ->outWithRank('ARGUMENT', 0)
             ->atomIs(array('String', 'Heredoc'))
             ->hasNoOut('CONCAT')
             ->back('results');
        $this->prepareQuery();

        // $wpdb->prepare("insert into $wpdb->prefix values (1,2,3)")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('OBJECT')
             ->_as('results')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs('prepare')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('String')
             ->tokenIs('T_QUOTE')
             // If it's a property, we accept $wpdb
             ->raw('not( where( __.out("CONCAT").hasLabel("Variable", "Array", "Member")
                             .not( where( __.out("OBJECT").has("code", '.$wpdb[0].') ) )
                             ) )')
             ->back('results');
        $this->prepareQuery();

        // $wpdb->prepare(<<<SQL insert into $wpdb->prefix values (1,2,3) SQL;)
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('OBJECT')
             ->_as('results')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs('prepare')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Heredoc')
             ->is('heredoc', true)
             // If it's a property, we accept $wpdb
             ->raw('where( __.out("CONCAT").hasLabel("Variable", "Array", "Member")
                             .not( where( __.out("OBJECT").has("code", '.$wpdb[0].') ) )
                              )')
             ->back('results');
        $this->prepareQuery();
        
        // $wpdb->prepare("insert into ".$wpdb->prefix." values (1,2,3)")
        $this->atomIs('Variableobject')
             ->codeIs('$wpdb', self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('OBJECT')
             ->_as('results')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->codeIs('prepare')
             ->outWithRank('ARGUMENT', 0)
             ->atomIs('Concatenation')
             // If it's a property, we accept $wpdb
             ->raw('not( where( __.out("CONCAT").hasLabel("Variable", "Array", "Member")
                             .not( where( __.out("OBJECT").has("code", '.$wpdb[0].') ) )
                             ) )')
             ->outIs('CONCAT')
             ->atomIs('String')
             ->tokenIs('T_QUOTE')
             // If it's a property, we accept $wpdb
             ->raw('not( where( __.out("CONCAT").hasLabel("String").has("token", "T_QUOTE")
                             .out("CONCAT").hasLabel("Variable", "Array", "Member")
                             .not( __.where( __.out("OBJECT").has("code", '.$wpdb[0].')))
                             ) )')
             ->back('results');
        $this->prepareQuery();
    }
}

?>
