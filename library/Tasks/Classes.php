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


namespace Tasks;

class Classes extends Tasks {
    public function run(\Exakat\Config $config) {
        $stats = $this->gremlin->queryColumn(<<<GREMLIN
g.idx('atoms')[['atom':'Class']]
.sideEffect{
    if (it.in.loop(1){true}{it.object.token == 'T_FILENAME'}.any()) {
        theFile = it.in.loop(1){true}{it.object.token == 'T_FILENAME'}.next().fullcode;
    } else {
        theFile = 'no file';
    }
}
.sideEffect{
    className = it.out('NAME').next().code;
    theExtends = it.out('EXTENDS').next().fullnspath;
    theImplements = it.out('IMPLEMENTS').fullnspath.toList();
//    theImplements = 1;
}

.transform{ ['className':className,
             'extends':theExtends,
             'implements':theImplements,
             'File':theFile,
             'Line':it.line,
             'Id':it.id];}

GREMLIN
);
        
        $fp = fopen('classes.csv', 'w+');
        $headers = array('File',
                         'Line',
                         'Id');
        fputcsv($fp, $headers);
        foreach($stats as $stat) {
            fputcsv($fp, (array) $stat);
        }
        fclose($fp);
        
        print count($stats)." rows found\n";
    }
}

?>
