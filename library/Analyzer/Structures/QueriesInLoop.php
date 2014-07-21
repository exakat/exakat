<?php

namespace Analyzer\Structures;

use Analyzer;

class QueriesInLoop extends Analyzer\Analyzer {
    public function analyze() {
        // for() { mysql_query(); }
        $this->atomIs(array("Foreach", "For", "While"))
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->code(array('mssql_query',
                          'mysqli_query',   
                          'mysqli_unbuffered_query',
                          'mysqli_db_query',
                          
                          'mysql_query',   
                          'mysql_unbuffered_query',
                          'mysql_db_query',
                          
                          'pg_query',
                          
                          'sqlite_array_query',
                          'sqlite_single_query',
                          'sqlite_unbuffered_query',
                          ))
             ->back('first')
             ;
        $this->prepareQuery();

        // for() { $pdo->query(); }
        $this->atomIs(array("Foreach", "For", "While"))
             ->outIs('BLOCK')
             ->atomInside('Functioncall')
             ->hasIn('METHOD')
             ->code('query') // PDO, cyrus
             ->back('first');
        $this->prepareQuery();

        // for() { somefunction(query()); }

    }
}

?>