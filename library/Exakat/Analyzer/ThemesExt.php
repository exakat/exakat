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


namespace Exakat\Analyzer;

use Exakat\Analyzer\Analyzer;
use AutoloadExt;

class ThemesExt {
    private $ext           = null;
    private $all           = array();
    private $themes        = array();

    static private $instanciated = array();
    
    public function __construct(AutoloadExt $ext) {
        $this->ext = $ext;
        
        foreach($ext->getAllAnalyzers() as $name => $list) {
            if (!isset($list['All'])) {
                continue; // ignore
            }
            $this->all[$name] = $list['All'];
            unset($list['All']);
            if (!empty($list)) {
                $this->themes[$name] = new ThemesExtra($list);
            }
        }
    }
    
    public function getThemeAnalyzers($theme = null) {
        if (empty($this->themes)) {
            return array();
        }
        
        $return = array();
        foreach($this->themes as $name => $t) {
            $return[] = $t->getThemeAnalyzers($theme);
        }
        
        return array_merge(...$return);
    }

    public function getThemeForAnalyzer($analyzer) {
        if (empty($this->themes)) {
            return array();
        }
        
        $return = array();
        foreach($this->themes as $name => $t) {
            $return[] = $t->getThemeForAnalyzer($analyzer);
        }
        
        return array_merge(...$return);

    }

    public function getThemesForAnalyzer($analyzer = null) {
        $return = array();
        foreach($this->themes as $theme => $extension) {
            $return[] = $extension->getThemesForAnalyzer($analyzer);
        }
        
        return empty($return) ? array() : array_merge(...$return);
    }

    public function getSeverities() {
        die(__METHOD__);
        $query = "SELECT folder||'/'||name AS analyzer, severity FROM analyzers";

        $return = array();
        $res = self::$sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['analyzer']] = empty($row['severity']) ? Analyzer::S_NONE : constant(Analyzer::class.'::'.$row['severity']);
        }

        return $return;
    }

    public function getTimesToFix() {
        die(__METHOD__);
        $query = "SELECT folder||'/'||name AS analyzer, timetofix FROM analyzers";

        $return = array();
        $res = self::$sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['analyzer']] = empty($row['timetofix']) ? Analyzer::S_NONE : constant(Analyzer::class.'::'.$row['timetofix']);
        }

        return $return;
    }

    public function getFrequences() {
        die(__METHOD__);
        $query = "SELECT analyzers.folder||'/'||analyzers.name AS analyzer, frequence / 100 AS frequence 
            FROM  analyzers
            LEFT JOIN analyzers_popularity 
                ON analyzers_popularity.id = analyzers.id";

        $return = array();
        $res = self::$sqlite->query($query);
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $return[$row['analyzer']] = empty($row['frequence']) ? 0 : $row['frequence'];
        }

        return $return;
    }
    
    public function listAllAnalyzer($folder = null) {
        if (empty($this->all)) {
            return array();
        }

        $return = array_merge(...array_values($this->all));
        if ($folder === null) {
            return $return;
        }
        return preg_grep("#$folder/#", $return);
    }

    public function listAllThemes() {
        $return = array();
        
        foreach($this->themes as $theme) {
            $return[] = $theme->listAllThemes();
        }

        return array_merge(...$return);
    }

    public function getClass($name) {
        // accepted names :
        // PHP full name : Analyzer\\Type\\Class
        // PHP short name : Type\\Class
        // Human short name : Type/Class
        // Human shortcut : Class (must be unique among the classes)

        if (strpos($name, '\\') !== false) {
            if (substr($name, 0, 16) === 'Exakat\\Analyzer\\') {
                $class = $name;
            } else {
                $class = "Exakat\\Analyzer\\$name";
            }
        } elseif (strpos($name, '/') !== false) {
            $class = 'Exakat\\Analyzer\\'.str_replace('/', '\\', $name);
        } elseif (strpos($name, '/') === false) {
            $found = $this->getSuggestionClass($name);

            if (empty($found)) {
                return false; // no class found
            }
            
            if (count($found) > 1) {
                return false;
            }
            
            $class = $found[0];
        } else {
            $class = $name;
        }

        if (!class_exists($class)) {
            return false;
        }

        $actualClassName = new \ReflectionClass($class);
        if ($class === $actualClassName->getName()) {
            return $class;
        } else {
            // problems with the case
            return false;
        }
    }

    public function getSuggestionThema(string $thema) {
        $list = $this->listAllThemes();

        return array_filter($list, function($c) use ($thema) {
            $l = levenshtein($c, $thema);
            return $l < 8;
        });
    }
    
    public function getSuggestionClass($name) {
        $list = array_merge(...array_values($this->all));
        
        return array_filter($this->listAllAnalyzer(), function($c) use ($name) {
            $l = levenshtein($c, $name);

            return $l < 8;
        });
    }

    public function getInstance($name, $gremlin = null, $config = null) {
        if ($analyzer = $this->getClass($name)) {
            if (!isset(self::$instanciated[$analyzer])) {
                self::$instanciated[$analyzer] = new $analyzer($gremlin, $config);
            }
            return self::$instanciated[$analyzer];
        } else {
            display("No such class as '$name'");
            return null;
        }
    }

}
?>
