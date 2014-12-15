<?php

namespace Analyzer\Functions;

use Analyzer;

class HardcodedPasswords extends Analyzer\Analyzer {
    public function analyze() {
        $passwords = array(
                           'mysql_connect'  => 3,
                           'mysqli_connect' => 3,
                           'ftp_login'      => 3,
                           'mssql_connect'  => 3,
                           'oci_connect'    => 2,
                           'imap_open'      => 3,
                           'cyrus_authenticate' => 8,
                           );
        
        $positions = array();
        foreach($passwords as $function => $position) {
            if (isset($positions[$position - 1])) {
                $positions[$position - 1][] = '\\'.$function;
            } else {
                $positions[$position - 1] = array('\\'.$function);
            }
        }

        foreach($positions as $position => $function) {
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->fullnspath($function)
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->hasRank($position)
                 ->atomIs('String')
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
