<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Exakat\Query;

use Exakat\Query\DSL\DSL;
use Exakat\Query\DSL\Command;

class Query {
    const STOP_QUERY = 'filter{ false; }';
    const NO_QUERY = 'filter{ true; }';
    
    private $analyzerId = null;
    private $id         = null;
    private $project    = null;
    private $analyzer   = null;
    private $php        = null;

    private $commands         = array();
    private $arguments        = array();
    private $query            = null;

    
    public function __construct($id, $project, $analyzer, $php) {
        $this->id       = $id;
        $this->project  = $project;
        $this->analyzer = $analyzer;
        $this->php      = $php;
    }

    public function __call($name, $args) {
        try {
            $command = DSL::factory($name);
            $this->commands[] = $command->run(...$args);
        } catch (UnknownDsl $e) {
            die(ici);
        }
        
        return $this;
    }

    public function prepareQuery($analyzerId) {
        assert($this->query === null, 'query is already ready');

        // @doc This is when the object is a placeholder for others.
        if (empty($this->commands)) {
            return true;
        }

        $this->analyzerId = $analyzerId;
        
        $commands = array_column($this->commands, 'gremlin');

        if (in_array(self::STOP_QUERY, $commands) !== false) {
            // any 'stop_query' is blocking
            return $this->query = '';
        }

        if (substr($commands[0], 0, 9) === 'hasLabel(') {
            $first = $commands[0];
            array_shift($commands);
            $this->query = "g.V().$first.groupCount(\"processed\").by(count()).as(\"first\")";
            if (!empty($commands)) {
                $this->query .= '.'.implode(".\n", $commands);
            }
        } elseif (substr($commands[0], 0, 39) === 'where( __.in("ANALYZED").has("analyzer"') {
            $first = $commands[0]; 
            array_shift($commands); 
            $arg0 = array_pop($this->commands[0]->arguments);
            unset($this->commands[0]);
            $this->query = 'g.V().hasLabel("Analysis").has("analyzer", within('.makeList($arg0).')).out("ANALYZED").as("first").groupCount("processed").by(count())';
            if (!empty($commands)) {
                $this->query .= '.'.implode(".\n", $commands);
            }
        } else {
            assert(false, 'No optimization : gremlin query in analyzer should have use g.V. ! '.$commands[1]);
        }

        $this->arguments = array_merge(...array_column($this->commands, 'arguments'));

        // search what ? All ?
        $this->query = <<<GREMLIN

{$this->query}

.dedup().groupCount("total").by(count()).addE("ANALYZED").from(g.V({$this->analyzerId})).cap("processed", "total")

// Query (#{$this->id}) for {$this->analyzer}
// php {$this->php} analyze -p {$this->project} -P {$this->analyzer} -v

GREMLIN;
        assert(!empty($this->analyzerId), "The analyzer Id for {$this->analyzerId} wasn't set. Can't save results.");
        
//        print_r($this);
    }
    
    public function prepareRawQuery() {
        $commands = array_column($this->commands, 'gremlin');
        $commands = implode('.', $commands);
        $this->arguments = array_merge(...array_column($this->commands, 'arguments'));

        $this->query = <<<GREMLIN
g.V().as('first').$commands

// Query (#{$this->id}) for {$this->analyzer}
// php {$this->php} analyze -p {$this->project} -P {$this->analyzer} -v\n

GREMLIN;

//        print_r($this);
    }
    
    public function getQuery() {
        assert($this->query !== null, "Null Query found!");
        return $this->query;
    }

    public function getArguments() {
        return $this->arguments;
    }

    public function printQuery() {
        $this->prepareQuery($this->analyzerId);
        print_r($this);
            echo $id, ")", PHP_EOL, print_r($query, true), print_r($this->queriesArguments[$id], true), PHP_EOL;

            krsort($this->queriesArguments[$id]);
            
            foreach($this->queriesArguments[$id] as $name => $value) {
                if (is_array($value)) {
                    if (is_array($value[key($value)])) {
                        foreach($value as $k => &$v) {
                            $v = "'''".$k."''':['''".implode("''', '''", $v)."''']";
                            $v = str_replace('\\', '\\\\', $v);
                        }
                        unset($v);
                        $query = str_replace($name, "[".implode(", ", $value)."]", $query);
                    } else {
                        $query = str_replace($name, "['".implode("', '", $value)."']", $query);
                    }
                } elseif (is_string($value)) {
                    $query = str_replace($name, "'".str_replace('\\', '\\\\', $value)."'", $query);
                } elseif (is_int($value)) {
                    $query = str_replace($name, (string) $value, $query);
                } else {
                    assert(false, 'Cannot process argument of type '.gettype($value).PHP_EOL.__METHOD__.PHP_EOL);
                }
            }
            
            echo $query, PHP_EOL, PHP_EOL;
        die();
    }

    public function debugQuery() {
        $methods = $this->methods;
        $arguments = $this->arguments;

        $nb = count($methods);
        for($i = 2; $i < $nb; ++$i) {
            $this->methods = array_slice($methods, 0, $i);
            $this->arguments = array_slice($arguments, 0, $i);
            $this->prepareQuery($this->analyzerId);
            $this->execQuery();
            echo  $this->rowCount, PHP_EOL;
            $this->rowCount = 0;
        }

        die();
    }
}
?>
