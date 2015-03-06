<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin\Query,
    Everyman\Neo4j\Index\NodeIndex;

class ConstantStructures implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $project = $config->project;

        $this->client = new Client();
    
        // Case for Literals
        $literals = array('Integer', 'Boolean', 'Real', 'Null', 'String', 'Void');
        foreach($literals as $literal) {
            $query = 'g.idx("atoms")[["atom":"'.$literal.'"]].each{ it.setProperty("constante", true)};';
            $this->query($query);
        }


        // Cases for Structures (all sub element are constante => structure is constante)
        $structures = array('Addition'       => array('LEFT', 'RIGHT'),
                            'Multiplication' => array('LEFT', 'RIGHT'),
                            'Keyvalue'       => array('KEY',  'VALUE'),
                            'Arguments'      => array('ARGUMENT'),
                            'Sequence'       => array('ELEMENT'),
                            'Break'          => array('BREAK'),
                            'Continue'       => array('CONTINUE'),
                            'Return'         => array('RETURN'),
                            );
        
        foreach($structures as $atom => $links) {
            print "$atom\n";
            $linksList = "'".join("', '", $links)."'";

            $query = <<<GREMLIN
g.idx("atoms")[["atom":"$atom"]]
    .hasNot('constante', true)
    .filter{ it.out($linksList).hasNot("constante", true).any() == false}
    .each{ it.setProperty("constante", true);}
GREMLIN;
            $this->query($query);
        }
        
        // case for Arguments
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]]
    .out('ARGUMENTS')
    .hasNot('constante', true)
    .filter{ it.out('ARGUMENT').hasNot("constante", true).any() == false}
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->query($query);

        // case for Assignation
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Assignation"]]
    .has('code', '=')
    .filter{ it.out("RIGHT").has("constante", true).any()}
    .sideEffect{ it.setProperty("constante", true); }
    .out('LEFT')
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->query($query);

        // case for array
        $query = <<<GREMLIN
g.idx("atoms")[["atom":"Functioncall"]]
    .has('token', 'T_ARRAY')
    .filter{ it.out("ARGUMENTS").has("constante", true).any()}
    .each{ it.setProperty("constante", true);}
GREMLIN;
        $this->query($query);
    }
    
    private function query($query, $retry = 1) {
        $params = array('type' => 'IN');
        try {
            $GremlinQuery = new Query($this->client, $query, $params);
            return $GremlinQuery->getResultSet();
        } catch (Exception $e) {
            $fp = fopen($config->projects_root.'/'.$config->project.'/log/build_root.log', 'a');
            fwrite($fp, $query."\n");
            fwrite($fp, $e->getMessage());
            fclose($fp);
        
            if ($retry) {
                sleep (3);
                return $this->query($query, 0);
            }
        
            die('died in '.__METHOD__."\n");
        }
    }

    private function logTime($step) {
        static $log, $begin, $end, $start;
    
        if ($log === null) {
            file_put_contents($this->project_dir.'/log/build_root.log', '');
            $log = fopen($this->project_dir.'/log/build_root.timing.csv', 'w+');
        }
        $end = microtime(true);
        if ($begin === null) { 
            $begin = $end; 
            $start = $end;
        }
    
        fwrite($log, $step."\t".($end - $begin)."\t".($end - $start)."\n");
        $begin = $end;
    }

}

?>