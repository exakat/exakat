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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;

class Plantuml extends Reports {
    const FILE_EXTENSION = 'puml';
    const FILE_FILENAME  = 'exakat';

    public function generate($folder, $name= self::FILE_FILENAME) {

        $res = $this->sqlite->query(<<<SQL
SELECT name, cit.id, extends, type, namespace, 
       (SELECT GROUP_CONCAT(method,   "\n")   FROM methods    WHERE citId = cit.id) AS methods,
       (SELECT GROUP_CONCAT(visibility || ' ' || case when static != 0 then 'static ' else '' end ||  case when value != '' then property || " = " || substr(value, 0, 40) else property end, "\n") FROM properties WHERE citId = cit.id) AS properties
    FROM cit
    JOIN namespaces
        ON namespaces.id = cit.namespaceId
SQL
        );
        $id = 0;
        $ids = array();
        $puml = array();
        $extends = array();
        $subgraphs = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            ++$id;
            $ids[$id] = $row['id'];
            $properties = array();

            $methods = $row['methods'];
            $methods = str_replace(' { /**/ } ' ,'', $methods);
            $methods = preg_replace(array('/public /is', '/protected /is', '/private /is', '/(static|abstract) /is', ),
                                    array('+',           '#',              '-',            '{\1}',                   ),
                                    $methods);
            
            if ($row['type'] == 'class' || $row['type'] === 'interface') {
                $type = $row['type'];
            } else {
                $type = 'abstract class'; // for traits
            }

            $properties = $row['properties'];
            $properties = preg_replace(array('/public /is', '/protected /is', '/private /is', '/(static|abstract) /is', ),
                                       array('+',           '#',              '-',            '{\1}',                   ),
                                       $properties);

            if ((int) $row['extends']) {
                $extends[$id] =  $row['extends'];
            }

            $object = $type.' "'.$row['name'].'" as Class'.$id.' { '.
"\n".$properties.
"\n".$methods.
"\n}";
            
            $puml[] = $object;
        }
        
        $puml = implode("\n", $puml)."\n\n";

        foreach($extends as $extending => $extended) {
            $puml .= "Class{$ids[$extended]} <|-- Class{$extending}\n";
        }

        $res = $this->sqlite->query(<<<'SQL'
SELECT implementing, implements, type FROM cit_implements
SQL
        );
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (!isset($ids[$row['implementing']])) { continue; }
            if (!isset($ids[$row['implements']])) { continue; }

            if ($row['type'] === 'implements') {
                $link = '<|..';
            } elseif ($row['type'] === 'extends') {
                $link = '<|--';
            } else {
                $link = '--';
            }
            
            $puml .= "Class{$ids[$row['implements']]} $link Class{$ids[$row['implementing']]}\n";
        }

        $dot = <<<PUML
@startuml

$puml

@enduml
PUML;

        if ($name === self::STDOUT) {
            echo $dot ;
        } else {
            file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $dot);
        }
    }

    private function str2dot($str) {
        return htmlspecialchars($str, ENT_COMPAT | ENT_HTML401 , 'UTF-8');
    }

    private function subgraphs($array, $level = 1, $nsname = '') {
        static $id = 0;
        $r = '';

        // Colors are managed with $level, thanks to colorscheme option.
        foreach($array as $key => $a) {
            ++$id;
            if (is_int($key)) {
                $r .= $a;
            } else {
                $r .= "subgraph cluster_$id { 
        style=filled;
        label=\"$nsname$key\";
        color=\"$level\";
        ";
                $r .= $this->subgraphs($a, $level + 1, $nsname.'\\\\'.$key);
                $r .= "}\n";
            }
        }

        return $r;
    }
}

?>