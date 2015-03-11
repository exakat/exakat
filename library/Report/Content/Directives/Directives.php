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


namespace Report\Content\Directives;

use Everyman\Neo4j\Client;

abstract class Directives implements \Iterator {
    const ON  = true;
    const OFF = false;
    
    const NO_SUGGESTION = '&nbsp';
    
    protected $directives = array();
    public    $name       = 'No Name';
    private   $position   = 0;
    private   $neo4j      = null;
    private   $hasDirective = false;

    public function __construct(Client $neo4j) {
        $this->neo4j    = $neo4j;
        $this->position = 0;
    }

    function rewind() {
        $this->position = 0;
    }

    function current() {
        return $this->directives[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function valid() {
        return isset($this->directives[$this->position]);
    }

    protected function checkPresence($analyzer) {
        $vertices = $this->query("g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('\\', '\\\\', $analyzer)."']].out.any()");
        return $this->hasDirective = ($vertices[0][0] === false ? self::OFF : self::ON);
    }

    public function query($query) {
        $params = array('type' => 'IN');
        try {
            $result = new \Everyman\Neo4j\Gremlin\Query($this->neo4j, $query, $params);
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $message = preg_replace('#^.*\[message\](.*?)\[exception\].*#is', '\1', $message);
            print "Exception : ".$message."\n";
        
            print $query."\n";
            die(__METHOD__);
        }
        return $result->getResultSet();
    }
    
    public function hasDirective() {
        return $this->hasDirective;
    }
    
    protected function deprecatedDirective($name, $version, $alternative = null) {
        if ($alternative === null) {
            $alternative = ' Do not use it anymore, in php.ini, .htaccess or with ini_* functions.';
        } else {
            $alternative = ' It is recommended to use the "'.$alternative.'" directive instead.';
        }

        return array('name'          => $name,
                     'suggested'     => 'Do not rely on it',
                     'documentation' => 'This directive is deprecated or removed since PHP '.$version.'. '.$alternative);
    }
    
    protected function extraConfiguration($name, $prefix) {
        return 
        array('name'          => 'Extra configurations',
              'suggested'     => Directives::NO_SUGGESTION,
              'documentation' => '<a href="http://php.net/manual/en/'.$prefix.'.configuration.php">'.$name.' runtime configuration</a>');
    }

}

?>