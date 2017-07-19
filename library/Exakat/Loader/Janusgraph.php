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


namespace Exakat\Loader;

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exceptions\LoadError;
use Exakat\Graph\Janusgraph as Graph;
use Exakat\Tasks\CleanDb;
use Exakat\Tasks\Load;
use Exakat\Tasks\Tasks;
use Exakat\Tokenizer\Token;

class Janusgraph {
    const CSV_SEPARATOR = ',';

    private $file_saved = 0;
    private $unlink = array();

    private static $count = -1; // id must start at 0 in batch-import

    private $tokenCounts   = array();

    private $labels = array();
    private $edges = array();
    
    private $config = null;
    
    private $calls = array();
    private $json = array();
    private $project = null;
    private $id = 0;
    
    private $cProperties = 0;
    private $cLinks = 0;
    private $cNodes = 0;

    private $janus = null;
    private $path = null;

    public function __construct($gremlin, $config) {
        $this->config = $config;
        
        // Force autoload
        $this->janus = new Graph($this->config);
        $this->path = $this->config->projects_root.'/projects/.exakat/janus.berkeley.graphson';

    }

    private function cleandDb() {
        display("Cleaning DB in janusgraph\n");
        $clean = new CleanDb(new Gremlin3($this->config), $this->config, Tasks::IS_SUBTASK);
        $clean->run();
    }

    public function finalize() {
        self::saveTokenCounts();

        foreach($this->calls as $type => $fnp) {
            foreach($fnp as $directions) {
                foreach($directions['definitions'] as $origin) {
                    foreach($origin as $ot) {
                        foreach($directions['calls'] as $destination) {
                            foreach($destination as $dt) {
                                if (isset($json[$ot]->outE->DEFINITION)) {
                                    $this->json[$ot]->outE->DEFINITION[] = (object) ["id" => $this->id++,"inV" => $dt];
                                } else {
                                    if (!isset($this->json[$ot])) {
                                        print "ID $ot doesn't exists\n";
                                    }
                                    if (!isset($this->json[$ot]->outE)) {
                                        print "ID $ot has no outE\n";
                                    }
                                    $this->json[$ot]->outE->DEFINITION = [ (object) ["id" => $this->id++,"inV" => $dt]];
                                }

                                if (isset($this->json[$dt]->inE->DEFINITION)) {
                                    $this->json[$dt]->inE->DEFINITION[] = (object) ["id" => $this->id++,"outV" => $ot];
                                } else {
                                    if (!isset($this->json[$dt])) {
                                        print "ID $dt doesn't exists\n";
                                    }
                                    $this->json[$dt]->inE->DEFINITION = [ (object) ["id" => $this->id++,"outV" => $ot]];
                                }
                            }
                        }
                    }
                }
            }
        }

        $jsonText = '';
        foreach($this->json as $j) {
            $this->cNodes++;
            $this->cLinks += count($j->outE);
            $jsonText .= html_entity_decode(json_encode($j)).PHP_EOL;
            assert(!json_last_error(), 'Error encoding '.$j->label.' : '.json_last_error_msg());
        }

        $this->cNodes++;
        $this->cLinks += count($this->project->outE);
        $jsonText .= html_entity_decode(json_encode($this->project)).PHP_EOL;
        
        $fp = fopen($this->path, 'a');
        $json = fwrite($fp, $jsonText);
        fclose($fp);

        display('loading nodes');
        print "Nodes : $this->cNodes\nEdges : $this->cLinks\nProperties  : $this->cProperties\n";
        
        $labels = array_map(function($x) { return "mgmt.makeVertexLabel('$x').make()"; }, array_merge(Token::$ATOMS, Token::$ATOMS_EXAKAT)); 
        $labels = join("\n", $labels);

        $propertyList = array_keys(end($this->json)->properties);
        $propertyList[] = 'intval';
        $propertyList[] = 'analyzer';
        $propertyList[] = 'notCompatibleWithPhpVersion';
        $propertyList[] = 'notCompatibleWithPhpConfiguration';
        $propertyList[] = 'virtual';
        $propertyList[] = 'root';
        
        $properties = array_map(function($x) { return "mgmt.makePropertyKey('$x').dataType(String.class).cardinality(Cardinality.SINGLE).make()"; }, $propertyList); 
        $properties = join("\n", $properties);

        $edges = array_map(function($x) { return "mgmt.makeEdgeLabel('$x').multiplicity(MULTI).make()"; }, array_merge(Token::$LINKS, Token::$LINKS_EXAKAT)); 
        $edges = join("\n", $edges);
        
        $query = <<<GREMLIN
mgmt = graph.openManagement()
$labels

$properties

$edges

mgmt.commit()

GREMLIN;
        $res = $this->janus->query($query);
//        print_r($res);

//        $begin = microtime(true);
        $res = $this->janus->query('graph.io(IoCore.graphson()).readGraph("'.$this->path.'"); g.V().count();');
        $end = microtime(true);
//        print 'Loading time : ' .number_format(($end - $begin) * 1000, 2)." ms \n";
//        print_r($res);
        display('loaded nodes');

        $query = <<<GREMLIN
graph.tx().rollback() //Never create new indexes while a transaction is active
mgmt = graph.openManagement()
atom = mgmt.getPropertyKey('atom')
mgmt.buildIndex('byAtom', Vertex.class).addKey(atom).buildCompositeIndex()
mgmt.commit()

mgmt.awaitGraphIndexStatus(graph, 'byAtom').call()
mgmt = graph.openManagement()
mgmt.updateIndex(mgmt.getGraphIndex("byAtom"), SchemaAction.REINDEX).get()
mgmt.commit()


GREMLIN;
        $res = $this->janus->query($query);
        print_r($res);

//        die();

        $this->cleanCsv();
        display('Cleaning CSV');

        return true;
    }

    private function cleanCsv() {
//        unlink($this->path);
    }

    private function saveTokenCounts() {
        $datastore = new Datastore($this->config);

        $datastore->addRow('tokenCounts', $this->tokenCounts);
    }

    private function escapeCsv($string) {
        return str_replace(array('\\', '"'), array('\\\\', '\\"'), $string);
    }

    public function saveFiles($exakatDir, $atoms, $links, $id0) {
        $booleanValues = array('alternative', 'heredoc', 'reference', 'variadic', 'absolute', 'enclosing', 'bracket', 'close_tag', 'aliased', 'boolean', 'constant');
        $integerValues = array('count', 'intval', 'args_max', 'args_min');
        
        $json = array();
        foreach($atoms as $atom) {
            $atom = (array) $atom;
            $label = $atom['atom'];
            $this->labels[$label] = 1;
            
            $object = array('id'    => $atom['id'],
                            'label' => $label,
                            'outE'  => new \stdClass(),
                            'inE'   => new \stdClass());
        
            $properties = array();
            foreach($atom as $l => $value) {
                if ($l === 'id') { continue; }
                if ($value === null) { continue; }

                if (!in_array($l, array('atom', 'rank', 'token', 'fullcode', 'code', 'line')) && 
                    !in_array($label, Load::$PROP_OPTIONS[$l])) {
                    continue;
                };
        
                if (in_array($l, $booleanValues)) {
                    $value = (boolean) $value;
                } elseif (in_array($l, $integerValues)) {
                    $value = (integer) $value;
                }
                $properties[$l] = [(object) ['id' => $this->id++, 'value' => $value]];
            }
        
            $this->cProperties += count($properties);
            $object['properties'] = $properties;
            $json[$atom['id']] = (object) $object;
        }
        
        foreach($links as $type => $a) {
            $this->edges[$type] = 1;
            foreach($a as $b) {
                foreach($b as $c) {
                    foreach($c as $d) {
                        if (isset($json[$d['origin']]->outE)) {
                            $json[$d['origin']]->outE->$type[] = (object) array("id" => $this->id++,"inV" => $d['destination']);
                        } else {
                            $json[$d['origin']]->outE     = (object) array( $type => [ (object) ["id" => $this->id++,"inV" => $d['destination']]]);
                        }

                        if (isset($json[$d['destination']]->inE)) {
                            $json[$d['destination']]->inE->$type[] = (object) array("id" => $this->id++,"outV" => $d['origin']);
                        } else {
                            $json[$d['destination']]->inE = (object) array( $type => [ (object) ["id" => $this->id++,"outV" => $d['origin']]]);
                        }
                    }
                }
            }
        }
        
        $jsonText = '';
        foreach($json as $j) {

            if (in_array($j->label, array('Functioncall', 'Function', 'Class', 'Classanonymous', 'Newcall', 'Variableobject', 
                                          'Identifier', 'Nsname', 'Interface', 'Trait', 'String', 'Constant', 'Arguments',
                                          'Variable', 'Variablearray', ))) {
                $this->json[$j->id] = $j;
            } elseif ($j->id === 1) {
                $this->project = $j;
            } else {
//                $j->code = htmlentities($j->code);
//                $j->fullcode = htmlentities($j->fullcode);;
//    $j->code = '';
//    $j->fullcode = '';
            
                $this->cNodes++;
                $this->cLinks += count($j->outE);
                $jsonText .= json_encode($j).PHP_EOL;
                assert(!json_last_error(), 'Error encoding '.$j->label.' : '.json_last_error_msg()."\n".print_r($j, true));
            }
        }
        
//        print $jsonText;
        
        $fp = fopen($this->path, 'a');
        $json = fwrite($fp, $jsonText);
        fclose($fp);
    }

    public function saveDefinitions($exakatDir, $calls) {
        //each time...
        $this->calls = $calls;
    }
}

?>
