<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Performances;

use Exakat\Analyzer\Analyzer;

class DoInBase extends Analyzer {
    public function analyze() {
        // TODO : Also extends to ++/-- and .

        // TODO : add more databases (methods and functions)

        $readingMethods = array('fetchArray',           // SQLITE3
                                'fetch', 'fetchobject', // PDO
                                'fetch_object', 'fetch_row', 'fetch_field', 'fetch_array', 'fetch_field', // mysqli
                                );

        // while($row = $res->fetchArray()) { $c += $row['e']}
        $this->atomIs('While')

             ->outIs('CONDITION')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Methodcall')
             ->outIs('METHOD')
             ->outIs('NAME')
             ->codeIs($readingMethods, self::TRANSLATE, self::CASE_INSENSITIVE)
             ->inIs('NAME')
             ->inIs('METHOD')
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'row')
             ->back('first')

             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Assignation')
             ->codeIs('+=')
             ->outIs('RIGHT')
             ->atomInsideNoDefinition(array('Variable', 'Variableobject', 'Variablearray'))
             ->samePropertyAs('code', 'row')

             ->back('first');
        $this->prepareQuery();

        $readingFunctions = array('pg_fetch_result', 'pg_fetch_row', 'pg_fetch_object', 'pg_fetch_assoc',    // PostGreSQL
                                  'oci_fetch_array', 'oci_fetch_row', 'oci_fetch_object', 'oci_fetch_assoc', // Oracle
                                  'sqlsrv_fetch', 'sqlsrv_fetch_object', 'sqlsrv_fetch_array',               //SQL_SRV
                                 );
        $readingFunctions = makeFullNsPath($readingFunctions);

        // while($row = $res->fetchArray()) { $c += $row['e']}
        $this->atomIs('While')

             ->outIs('CONDITION')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->fullnspathIs($readingFunctions)
             ->inIs('RIGHT')
             ->outIs('LEFT')
             ->atomIs('Variable')
             ->savePropertyAs('code', 'row')
             ->back('first')

             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Assignation')
             ->codeIs(array('+='), self::TRANSLATE, self::CASE_SENSITIVE)
             ->outIs('RIGHT')
             ->atomInsideNoDefinition(array('Variable', 'Variableobject', 'Variablearray'))
             ->samePropertyAs('code', 'row')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
