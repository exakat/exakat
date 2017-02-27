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

namespace Exakat\Reports;

use \Exakat\Graph\Gremlin3;
use \Exakat\Config;

class FileDependencies extends Reports {
    const FILE_EXTENSION = 'dot';
    const FILE_FILENAME  = 'dependencies';

    public function generate($folder, $name= 'dependencies') {
        $dot = '';

        $res = $this->sqlite->query('SELECT * FROM filesDependencies');

        $colors = array('INCLUDE'          => 'green',
                        'STATICMETHODCALL' => 'purple',
                        'STATICCONSTANT'   => 'purple',
                        'STATICPROPERTY'   => 'purple',
                        'USE'              => 'orange',
                        'NEW'              => 'blue',
                        'EXTENDS'          => 'red',
                        'IMPLEMENTS'       => 'red',
                        'FUNCTIONCALL'     => 'blue',
                        'CONSTANT'         => 'blue');

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $dot  .= "\"$row[including]\" -> \"$row[included]\" [label=\"$row[type]\" color={$colors[$row['type']]} ];\n";
        }

        $dot = " digraph graphname {
    $dot
     }";

        file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $dot);
    }
}

?>