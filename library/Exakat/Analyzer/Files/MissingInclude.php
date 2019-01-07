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

namespace Exakat\Analyzer\Files;

use Exakat\Analyzer\Analyzer;

class MissingInclude extends Analyzer {
    public function analyze() {
        $this->atomIs('File')
             ->values('fullcode');
        $files = $this->rawQuery()
                      ->toArray();
        if(empty($files)) {
            return ;
        }

        $this->atomIs('Include')
             ->outIs('ARGUMENT')
             ->outIsIE('CODE')
             ->atomIs('String')
             ->hasNoOut('CONCAT')
             ->IsNot('noDelimiter', '')
             ->savePropertyAs('noDelimiter', 'including')
             ->goToFile()
             ->raw(<<<GREMLIN
filter{
    inclusions = ***;
    inclusions_lc = inclusions.collect{ it.toLowerCase(); }

    file = it.get().value('fullcode').toString();
    dirs = file.tokenize('/').dropRight(1);
    if (dirs.size() > 0) {
        path = '/' + dirs.join('/') + '/';
    } else {
        // Root
        path = '/';
    }

    if (including.getAt(0) != '/') {
        including = path + including;
    }
    
    including2 = including.replaceAll('/\\\./', '/').replaceAll('/[^/]+/\\\.\\\./', '/');
    while( including2 != including) {
        including = including2;
        including2 = including.replaceAll('/\\\./', '/').replaceAll('/[^/]+/\\\.\\\./', '/');
    }

    !(including.toLowerCase() in inclusions_lc);
}
GREMLIN
, $files
)
             ->back('first');
        $this->prepareQuery();

        $this->atomIs('Include')
             ->outIs('ARGUMENT')
             ->outIsIE('CODE')
             ->atomIs('Concatenation')
             // checks
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('CONCAT')
                             ->hasNo('noDelimiter')
                             ->not(
                                $this->side()
                                     ->filter(
                                        $this->side()
                                             ->functioncallIs('\\dirname')
                                      )
                             )
                     )
             )

             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('CONCAT')
                             ->outIs('CONCAT')
                     )
             )
             ->raw(<<<GREMLIN
sideEffect{ 
    including = []; 
    ignore = false;
    it.get().vertices(OUT, "CONCAT")
      .sort{it.value("rank")}
      .each{
            if (it.label() == "Magicconstant") {
                including.add(it.value("noDelimiter"));
            } else if (it.label() == "Identifier") {
                including.add(it.value("noDelimiter"));
            } else if (it.label() == "Nsname") {
                including.add(it.value("noDelimiter"));
            } else if (it.label() == "Functioncall" && 
                       it.value("fullnspath") == "\\\\dirname") {
                dirname = it.vertices(OUT, "ARGUMENT").findAll{ it.value("rank") == 0;}[0];
                nb = it.vertices(OUT, "ARGUMENT").findAll{ it.label() == "Integer";}.findAll{ it.value("rank") == 1;}[0];
                if (nb == null) {
                    loop = 1;
                } else {
                    loop = Math.max(1, nb.value("noDelimiter"));
                }
                
                while( dirname.label() == 'Functioncall') {
                    dirname = dirname.vertices(OUT, "ARGUMENT").findAll{ it.value("rank") == 0;}[0];
                    nb = dirname.vertices(OUT, "ARGUMENT").findAll{ it.label() == "Integer";}.findAll{ it.value("rank") == 1;}[0];
                    
                    if (nb == null) {
                        ++loop;
                    } else {
                        loop += Math.max(1, nb.value("noDelimiter"));
                    }
                };

                if ('noDelimiter' in dirname.keys()) {
                    dirs = dirname.value("noDelimiter").split("/");
                    
                    if (loop <= 0) {
                        dirs = '';
                    } else if (dirs.size() > loop) {
                        dirs = dirs.dropRight(loop).join("/");
                    } else {
                        dirs = '/';
                    }

                    including.add(dirs);
                } else {
                    // This just ignore the path
                    ignore = true;
                }
            } else {
                including.add(it.value("noDelimiter"));
            }
        }; 
    including = including.join(""); 
}
.filter{ !ignore ; }
GREMLIN
)
             ->goToFile()
             ->raw(<<<GREMLIN
filter{
    inclusions = ***;
    inclusions_lc = inclusions.collect{ it.toLowerCase(); }

    file = it.get().value("fullcode").toString();
    dirs = file.tokenize("/").dropRight(1);
    if (dirs.size() > 0) {
        path = "/" + dirs.join("/") + "/";
    } else {
        // Root
        path = "/";
    }

    if (including.getAt(0) != "/") {
        including = path + including;
    }
    
    including = including.replaceAll("/\\\./", "/");
    including = including.replaceAll("/[^/]+/\\\.\\\./", "/");

    !(including.toLowerCase() in inclusions_lc);
}
GREMLIN
, $files
)
             ->back('first')
             ;
        $this->prepareQuery();
    }
}

?>
