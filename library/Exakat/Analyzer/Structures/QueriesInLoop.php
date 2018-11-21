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


namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class QueriesInLoop extends Analyzer {
    public function analyze() {
        // for() { mysql_query(); }
        $this->atomIs(array('Foreach', 'For', 'While'))
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Functioncall')
             ->codeIs(array('cubrid_query',
                            'cubrid_prepare',
                            'cubrid_execute',
                            'cubrid_bind',
                            
                            'mssql_query',
                            
                            'mysqli_query',
                            'mysqli_unbuffered_query',
                            'mysqli_db_query',

                            'mysqli_stmt_bind_param',
                            'mysqli_stmt_execute',
                            'mysqli_prepare',
                            
                            'mysql_query',
                            'mysql_unbuffered_query',
                            'mysql_db_query',
                            
                            'oci_execute',
                            'oci_parse',
                            'oci_bind_array_by_name',
                            'oci_bin_by_name',
                            
                            'pg_query',
                            'pg_prepare',
                            'pg_execute',
                            
                            'sqlsrv_execute',
                            'sqlsrv_prepare',
                            'sqlsrv_query',

                            'sqlite_array_query',
                            'sqlite_single_query',
                            'sqlite_unbuffered_query',
                            
                            ))
             ->back('first');
        $this->prepareQuery();

        // for() { $pdo->query(); }
        $this->atomIs(self::$LOOPS_ALL)
             ->outIs('BLOCK')
             ->atomInsideNoDefinition('Functioncall')
             ->hasIn('METHOD')
             ->codeIs('query') // PDO, cyrus
             ->back('first');
        $this->prepareQuery();

        // for() { somefunction(query()); }

    }
}

?>
