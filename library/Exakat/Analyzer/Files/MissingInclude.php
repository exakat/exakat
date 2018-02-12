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

namespace Exakat\Analyzer\Files;

use Exakat\Analyzer\Analyzer;

class MissingInclude extends Analyzer {
    public function analyze() {
        $this->atomIs('File')
             ->values('fullcode');
        $files = $this->rawQuery()->toArray();

        $this->atomIs('Include')
             ->outIs('ARGUMENT')
             ->outIsIE('CODE')
             ->atomIs('String')
             ->hasNoOut('CONCAT')
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
             ->raw('not(where( __.out("CONCAT").not(has("noDelimiter")).not( hasLabel("Functioncall").has("fullnspath", "\\\\dirname") ) ) )')
             ->raw('not(where( __.out("CONCAT").out("CONCAT") ) )')
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
                loop = 1;
                dirname = it.vertices(OUT, "ARGUMENT").next();
                
                while( dirname.label() == 'Functioncall') {
                    dirname = dirname.vertices(OUT, "ARGUMENT").next();
                    ++loop;
                };
                if ('noDelimiter' in dirname.keys()) {
                    dirs = dirname.value("noDelimiter").split("/").dropRight(loop);

                    if (dirs.size() > 0) {
                        including.add(dirs.join("/"));
                    } 
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
             ->back('first');
        $this->prepareQuery();
    }
}

?>
