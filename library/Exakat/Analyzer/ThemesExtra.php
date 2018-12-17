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

class ThemesExtra {
    private $extra_themes  = array();

    static private $instanciated = array();
    
    public function __construct(array $extra_themes = array()) {
        $this->extra_themes = $extra_themes;
    }
    
    public function getThemeAnalyzers($theme = null) {
        // Main installation
        if ($theme === null) {
            return array_unique(array_merge(...$this->extra_themes));
        } elseif (is_array($theme)) {
            $return = array();
            foreach($theme as $t) {
                $return[] = $this->extra_themes[$t] ?? array();
            }
            return array_unique(array_merge(...$return));
        } elseif ($theme === 'Random') {
            $shorList = array_keys($this->extra_themes);
            shuffle($shorList);
            $theme = $shorList[0];
            display( "Random theme is : $theme\n");

            return $this->extra_themes[$theme] ?? array();
        } else {
            return $this->extra_themes[$theme] ?? array();
        }
    }

    public function getThemeForAnalyzer($analyzer) {
        $return = array();
        foreach($this->extra_themes as $theme => $analyzers) {
            if (in_array($analyzer, $analyzers)) {
                $return[] = $theme;
            }
        }
        
        return $return;
    }

    public function getThemesForAnalyzer($analyzer = '') {
        $return = array();
        foreach($this->extra_themes as $theme => $analyzers) {
            if (in_array($analyzer, $analyzers)) {
                $return[] = $theme;
            }
        }
        
        return $return;
    }

    public function getSeverities() {
        die(__METHOD__);
    }

    public function getTimesToFix() {
        die(__METHOD__);
    }

    public function getFrequences() {
        die(__METHOD__);
    }

    public function listAllAnalyzer($folder = null) {
        // This is not providing any new analysers.
        return array();
    }

    public function listAllThemes() {
        return array_keys($this->extra_themes);
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

    public function getSuggestionThema($thema) {
        $list = $this->listAllThemes();

        return array_filter($list, function($c) use ($thema) {
            $l = levenshtein($c, $thema);
            return $l < 8;
        });
    }
    
    public function getSuggestionClass($name) {
        return array_filter($this->listAllAnalyzer(), function($c) use ($name) {
            $l = levenshtein($c, $name);

            return $l < 8;
        });
    }

    public static function resetCache() {
        self::$instanciated = array();
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
