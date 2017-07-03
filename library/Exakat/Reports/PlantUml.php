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

use Exakat\Analyzer\Analyzer;

class PlantUml extends Reports {
    const FILE_EXTENSION = 'puml';
    const FILE_FILENAME  = 'exakat.puml';

public function generate($folder, $name= 'puml') {

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
        $links = array();
        $extends = array();
        $colors = array('class' => 'darkorange', 'trait' => 'gold', 'interface' => 'skyblue');
        $subgraphs = array();

        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            ++$id;
            $ids[$id] = $row['id'];
            $properties = array();

            $methods = $row['methods'];
            $methods = str_replace(' { /**/ } ' ,'', $methods);
            $methods = preg_replace('/public /is' ,   '+', $methods);
            $methods = preg_replace('/protected /is' ,'#', $methods);
            $methods = preg_replace('/private /is' ,  '-', $methods);
            $methods = preg_replace('/(static|abstract) /is' ,  '{\1}', $methods);
            
            if ($row['type'] == 'class' || $row['type'] === 'interface') {
                $type = $row['type'];
            } else {
                $type = 'abstract class'; // for traits
            }

            $properties = $row['properties'];
            $properties = preg_replace('/public /is' ,   '+', $properties);
            $properties = preg_replace('/protected /is' ,'#', $properties);
            $properties = preg_replace('/private /is' ,  '-', $properties);
            $properties = preg_replace('/(static|abstract) /is' ,  '{\1}', $properties);

            if ((int) $row['extends']) {
                $extends[$id] =  $row['extends'];
            }

            $object = $type.' "'.$row['name'].'" as Class'.$id.' { '.
"\n".$properties.
"\n".$methods.
"\n}";
            
            $puml[] = $object;
        }
        
        $puml = join("\n", $puml)."\n\n";

        foreach($extends as $extends => $extended) {
            $puml .= "Class{$ids[$extended]} <|-- Class{$extends}\n";
        }

        $res = $this->sqlite->query(<<<SQL
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
                var_dump($row['type']);
                $link = '--';
            }
            
            $puml .= "Class{$ids[$row['implements']]} $link Class{$ids[$row['implementing']]}\n";
//            $links[] = $ids[$row['implementing']]." -> \"".$ids[$row['implements']]."\" [label=\"$row[type]\"];";
        }

        $dot = <<<PUML
@startuml

$puml

@enduml
PUML;
    
        file_put_contents($folder.'/'.$name.'.'.self::FILE_EXTENSION, $dot);
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