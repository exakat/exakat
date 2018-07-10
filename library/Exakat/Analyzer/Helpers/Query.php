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


namespace Exakat\Analyzer\Helpers;

class Query {
    const STOP_QUERY = 'filter{ false; }';
    
    private $analyzerId = null;
    private $id         = null;
    private $project    = null;
    private $analyzer   = null;
    private $php        = null;
    
    private $methods          = array('as("first")');
    private $arguments        = array();
    private $query            = null;
    
    public function __construct($id, $project, $analyzer, $php) {
        $this->id       = $id;
        $this->project  = $project;
        $this->analyzer = $analyzer;
        $this->php      = $php;
    }

    public function stopQuery() {
        $this->methods[] = self::STOP_QUERY;
    }

    public function addMethod($method, $arguments = array()) {
        if ($arguments === array()) { // empty, but won't mistake 0 for nothing
            $this->methods[] = $method;
            return $this;
        }
        
        assert(substr_count($method, '***') == func_num_args() - 1, substr_count($method, '***').' placeholders for '.(func_num_args() - 1).' arguments, in '.$method);
        
        if (func_num_args() >= 2) {
            $arguments = func_get_args();
            array_shift($arguments);
            $argnames = array(str_replace('***', '%s', $method));
            foreach($arguments as $arg) {
                $argname = 'arg'.count($this->arguments);
                $this->arguments[$argname] = $arg;
                $argnames[] = $argname;
            }
            $this->methods[] = call_user_func_array('sprintf', $argnames);
            return $this;
        }

        // one argument
        $argname = 'arg'.count($this->arguments);
        $this->arguments[$argname] = $arguments;
        $this->methods[] = str_replace('***', $argname, $method);
        
        return $this;
    }

    public function prepareQuery($analyzerId) {
        assert($this->query === null, 'query is already ready');
        $this->analyzerId = $analyzerId;

        // @doc This is when the object is a placeholder for others.
        if (count($this->methods) <= 1) { 
            return true; 
        }
        
        if (in_array(self::STOP_QUERY, $this->methods) !== false) {
            // any 'stop_query' is blocking
            return $this->query = '';
        }

        if (substr($this->methods[1], 0, 9) === 'hasLabel(') {
            $first = $this->methods[1];
            array_splice($this->methods, 1,1);
            $query = implode('.', $this->methods);
            $query = "g.V().$first.groupCount(\"processed\").by(count()).$query";
        } elseif (substr($this->methods[1], 0, 39) === 'where( __.in("ANALYZED").has("analyzer"') {
            $first = array_shift($this->methods); // remove first
            array_shift($this->methods); // remove second
            $query = implode('.', $this->methods);
            $arg0 = $this->arguments['arg0'];
            $query = 'g.V().hasLabel("Analysis").has("analyzer", within('.makeList($arg0).')).out("ANALYZED").as("first").groupCount("processed").by(count())'
                     .(empty($query) ? '' : '.'.$query);
            unset($this->methods[1]);
        } else {
            assert(false, 'No optimization : gremlin query in analyzer should have use g.V. ! '.$this->methods[1]);
        }
        
        // search what ? All ?
        $query = <<<GREMLIN

{$query}

GREMLIN;
        assert(!empty($this->analyzerId), "The analyzer Id for {$this->analyzerId} wasn't set. Can't save results.");
        $query .= '.dedup().groupCount("total").by(count()).addE("ANALYZED").from(g.V('.$this->analyzerId.')).cap("processed", "total")

// Query (#'.$this->id.') for '.$this->analyzer.'
// php '.$this->php." analyze -p ".$this->project.' -P '.$this->analyzer." -v".PHP_EOL;

        $this->query = $query;
    }
    
    public function prepareRawQuery() {
        $this->query = implode('.', $this->methods);
        $this->query = 'g.V().'.
                 $this->query.
                 '
// Query (#'.$this->id.') for '.$this->analyzer.'
// php '.$this->php." analyze -p ".$this->project.' -P '.$this->analyzer." -v".PHP_EOL;
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
        
        foreach($this->queries as $id => $query) {
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
        }
        die();
    }
}
?>
