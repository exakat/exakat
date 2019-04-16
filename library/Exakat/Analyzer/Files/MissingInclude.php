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

namespace Exakat\Analyzer\Files;

use Exakat\Analyzer\Analyzer;

class MissingInclude extends Analyzer {
    protected $constant_or_variable_name = 100;
    
    public function analyze() {
        $files = array_merge(self::$datastore->getCol('files', 'file'),
                             self::$datastore->getCol('ignoredFiles', 'file'));

        if (empty($files)) {
            $this->atomIs('File')
                  ->values('fullcode');
            $files = $this->rawQuery()->toArray();
        }

        $this->atomIs('Include')
              ->outIs('ARGUMENT')
              ->outIsIE('CODE')
              ->_as('include')
              ->goToInstruction('File')
              ->_as('file')
              ->select(array('file'    => 'fullcode',
                             'include' => 'fullcode'));
        $result = $this->rawQuery();
//        print $result->count().' found'.PHP_EOL;

        $inclusions = array();
        $missing = array();
        foreach($result->toArray() as $row) {
            if ($this->searchFile($row['include'], $files, $row['file'])) { continue; }

            $notFound = $row['include'];
            $missing[$notFound] = 1;
            if (isset($inclusions[$row['file']])) {
                $inclusions[$row['file']][] = $notFound;
            } else {
                $inclusions[$row['file']] = array($notFound);
            }
        }
        $missing = array_keys($missing);
//        print_r($inclusions);

//        print (count($inclusions, true) - count($inclusions)).' missing'.PHP_EOL;

        $this->atomIs('Include')
              ->outIs('ARGUMENT')
              ->outIsIE('CODE')
              ->fullcodeIs($missing)
              ->back('first');
        $result = $this->prepareQuery();
        return;

        print_r($inclusions);
        return;

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
             ->back('first');
        $this->prepareQuery();
    }
    
    private function searchFile($file, $files, $including) {
        if (empty($file)) { return false; }

        $bits = explode(' . ', $file);
        $vars = $this->config->Files_MissingInclude;
        $vars['__FILE__'] = $including;

        $__dir__ = dirname($including);
        if ($__dir__ === '/') { $__dir__ = ''; }
        $vars['__DIR__'] = $__dir__;
        $vars['dirname(__FILE__)'] = $__dir__;

        $__dir__ = dirname($__dir__);
        if ($__dir__ === '/') { $__dir__ = ''; }
        $vars['dirname(__DIR__)'] = $__dir__;
        $vars['dirname(__DIR__, 1)'] = $__dir__;
        $vars['dirname(dirname(__FILE__))'] = $__dir__;

        $__dir__ = dirname($__dir__);
        if ($__dir__ === '/') { $__dir__ = ''; }
        $vars['dirname(dirname(__DIR__))'] = $__dir__;
        $vars['dirname(__DIR__, 2)'] = $__dir__;
        $vars['dirname(dirname(dirname(__FILE__)))'] = $__dir__;

        $__dir__ = dirname($__dir__);
        if ($__dir__ === '/') { $__dir__ = ''; }
        $vars['dirname(dirname(dirname(__FILE__)))'] = $__dir__;
        $vars['dirname(dirname(dirname(__DIR__)))'] = $__dir__;
        $vars['dirname(dirname(dirname(dirname(__FILE__))))'] = $__dir__;
        $vars['dirname(__DIR__, 3)'] = $__dir__;

        if (count($bits) == 1) {
            $file = trim($file, '"\'');
            $file = str_replace(array_keys($vars),
                                array_values($vars),
                                $file
                                );
        } else {
            foreach($bits as &$bit) {
                if ($bit[0] === '"' || $bit[0] === "'") {
                    $bit = trim($bit, '"\'');
                } else {
                    $bit = $vars[$bit] ?? $bit;
                }
            }
            unset($bit);
            $file = implode('', $bits);
        }
        
        // simplify /dir/../ => /
        while(preg_match('|^(.*/)[^/\.]+?/\.\./(.*)$|', $file, $r)) {
            $file = $r[1].$r[2];
        }

        if (in_array($file, $files)) { return true; }
        
        if (substr($file, 0, 2) === './') {
            if (in_array(substr($file, 1), $files)) { return true; }
            
            if (in_array(dirname($including).substr($file, 1), $files)) { return true; }
        }

        if (substr($file, 0, 3) === '../' && in_array(substr($file, 2), $files)) { return true;}

        if (substr($file, 0, 6) === '../../' && in_array(substr($file, 5), $files)) { return true;}

        if (substr($file, 0, 9) === '../../../' && in_array(substr($file, 8), $files)) { return true;}

        if (isset($file[0]) && $file[0] !== '/') {

            if (in_array(dirname($including).'/'.$file, $files)) { return true; }
        }
        
        if (strpos($file, '$')  !== false) { return true;}
        if (strpos($file, '::') !== false) { return true;}
        if (strpos($file, '->') !== false) { return true;}

        return false;
    }
}

?>
