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

namespace Exakat\Reports;

use Exakat\Analyzer\Analyzer;
use Exakat\Reports\Helpers\Results;

class Manual extends Reports {
    const FILE_EXTENSION = 'md';
    const FILE_FILENAME  = 'manual.exakat';
    
    private $summary = array('Structures'  => array(),
                             'Expressions' => array(),
                             'Values'      => array(),
                             );

    public function _generate($analyzerList) {
        $md = '';
        
        $md .= $this->generateExceptionTree();
        $md .= $this->generateConstants();

        $md .= $this->generateDynamicExpression();

        $md .= $this->generateErrorMessages();
        $md .= $this->generateSQL();
        $md .= $this->generateURL();
        $md .= $this->generateEmail();
        $md .= $this->generateRegex();
        $md .= $this->generateIncoming();
        $md .= $this->generateHash();
        $md .= $this->generateMime();
        
        $summary = 'Table of content'.PHP_EOL.'---'.PHP_EOL.PHP_EOL;
        foreach($this->summary as $section => $list) {
            $summary .= '+ '.$section.PHP_EOL;
            $summary .= '   + '.implode(PHP_EOL.'   + ', $list).PHP_EOL;
        }
        
        $md = $summary.PHP_EOL.'---'.PHP_EOL.$md;
        
        return $md;
    }

    private function flatten($array) {
        return implode("\n+ ", $array);
    }

    private function escapeMd($string) {
        return str_replace('_', '\\_', $string);
    }
    
    private function generateConstants() {
        $total = 0;
        $constants = '';
        $md = '';
        
        $res = $this->sqlite->query('SELECT * FROM constants');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $constants .= '+ `'.$row['constant'].'` = '.$this->escapeMd($row['value']).PHP_EOL;
            ++$total;
        }
        
        if (empty($constants)) {
            return '';
        }
        
        $this->summary['Structures'][] = '[Constants](#Constants)';
        $md .= '## Constants'.PHP_EOL.PHP_EOL;
        $md .= $total.' constants'.PHP_EOL.PHP_EOL;
        $md .= $constants.PHP_EOL;
    
       return $md;
    }

    private function generateDynamicExpression() {
        $total = 0;
        $expressions = '';
        $md = '';
        
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Structures/DynamicCalls"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $expressions .= '+ `'.$row['fullcode'].'` in '.$this->escapeMd($row['file']).' : '.$this->escapeMd($row['line']).PHP_EOL;
            ++$total;
        }
        
        if (empty($expressions)) {
            return '';
        }
        
        $this->summary['Expressions'][] = '[Dynamic expressions](#dynamic-expressions)';
        $md .= '## Dynamic expressions'.PHP_EOL.PHP_EOL;
        $md .= $total.' dynamic expressions'.PHP_EOL.PHP_EOL;
        $md .= $expressions.PHP_EOL;
    
       return $md;
    }
    
    private function generateErrorMessages() {
        $total = 0;
        $errors = '';
        $md = '';
        
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Structures/ErrorMessages"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $errors .= '+ `'.$row['fullcode'].'` in '.$this->escapeMd($row['file']).' : '.$this->escapeMd($row['line']).PHP_EOL;
            ++$total;
        }
        
        if (empty($errors)) {
            return '';
        }
        
        $this->summary['Values'][] = '[Error messages](#error-messages)';
        $md .= '## Error messages'.PHP_EOL.PHP_EOL;
        $md .= $total.' error messages'.PHP_EOL.PHP_EOL;
        $md .= $errors.PHP_EOL;
    
       return $md;
    }

    private function generateSQL() {
        return $this->generateGeneric('Type/SQL', 'SQL');
    }

    private function generateURL() {
        return $this->generateGeneric('Type/URL', 'URL');
    }

    private function generateEmail() {
        return $this->generateGeneric('Type/Email', 'Email');
    }
    
    private function generateIncoming() {
        return $this->generateGeneric('Type/GPCIndex', 'Incoming variables');
    }

    private function generateHash() {
        return $this->generateGeneric('Type/Md5String', 'Hash String');
    }

    private function generateMime() {
        return $this->generateGeneric('Type/Mime', 'Mime type');
    }

    private function generateGeneric($analyzer, $name) {
        $total = 0;
        $url = '';
        $md = '';
        
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="'.$analyzer.'"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $url .= '+ `'.$row['fullcode'].'` in '.$this->escapeMd($row['file']).' : '.$this->escapeMd($row['line']).PHP_EOL;
            ++$total;
        }
        
        if (empty($url)) {
            return '';
        }
        
        $id = strtolower($name);
        $this->summary['Values'][] = '['.$name.'](#'.$id.')';
        $md .= '## '.$name.PHP_EOL.PHP_EOL;
        $md .= $total.' '.$name.PHP_EOL.PHP_EOL;
        $md .= $url.PHP_EOL;
    
       return $md;
    }
    
    private function generateRegex() {
        $total = 0;
        $url = '';
        $md = '';
        
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="Type/Regex"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $url .= '+ `'.stripslashes($row['fullcode']).'` in '.$this->escapeMd($row['file']).' : '.$this->escapeMd($row['line']).PHP_EOL;
            ++$total;
        }
        
        if (empty($url)) {
            return '';
        }
        
        $this->summary['Values'][] = '[Regex](#regex)';
        $md .= '## Regex'.PHP_EOL.PHP_EOL;
        $md .= $total.' Regex'.PHP_EOL.PHP_EOL;
        $md .= $url.PHP_EOL;
    
       return $md;
    }

    private function generateExceptionTree() {
        $exceptions = array (
  'Throwable' => 
  array (
    'Error' => 
    array (
      'ParseError' => 
      array (
      ),
      'TypeError' => 
      array (
        'ArgumentCountError' => 
        array (
        ),
      ),
      'ArithmeticError' => 
      array (
        'DivisionByZeroError' => 
        array (
        ),
      ),
      'AssertionError' => 
      array (
      ),
    ),
    'Exception' => 
    array (
      'ErrorException' => 
      array (
      ),
      'ClosedGeneratorException' => 
      array (
      ),
      'DOMException' => 
      array (
      ),
      'LogicException' => 
      array (
        'BadFunctionCallException' => 
        array (
          'BadMethodCallException' => 
          array (
          ),
        ),
        'DomainException' => 
        array (
        ),
        'InvalidArgumentException' => 
        array (
        ),
        'LengthException' => 
        array (
        ),
        'OutOfRangeException' => 
        array (
        ),
      ),
      'RuntimeException' => 
      array (
        'OutOfBoundsException' => 
        array (
        ),
        'OverflowException' => 
        array (
        ),
        'RangeException' => 
        array (
        ),
        'UnderflowException' => 
        array (
        ),
        'UnexpectedValueException' => 
        array (
        ),
        'PDOException' => 
        array (
        ),
      ),
      'PharException' => 
      array (
      ),
      'ReflectionException' => 
      array (
      ),
    ),
  ),
);
        $list = array();

        $theTable = '';
        $total = 0;
        $res = $this->sqlite->query('SELECT fullcode, file, line FROM results WHERE analyzer="Exceptions/DefinedExceptions"');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            ++$total;
            if (!preg_match('/ extends (\S+)/', $row['fullcode'], $r)) {
                continue;
            }
            $parent = strtolower($r[1]);
            if ($parent[0] != '\\') {
                $parent = '\\'.$parent;
            }

            if (!isset($list[$parent])) {
                $list[$parent] = array();
            } 
            
            $list[$parent][] = $row['fullcode'];
        }
        
        foreach($list as &$l) {
            sort($l);
        }
        $theTable = $this->tree2ul($exceptions, $list);

        $this->summary['Structures'][] = '[Exception Tree](#exception tree)';
        $md = '## Exception Tree'.PHP_EOL.PHP_EOL;
        $md .= $total.' exceptions'.PHP_EOL.PHP_EOL;
        $md .= $theTable.PHP_EOL;
    
       return $md;
    }

    private function tree2ul($tree, $display, $level = 0) {
        if (empty($tree)) {
            return '';
        }
        
        $return = '';
        
        foreach($tree as $k => $v) {
            $phpTree = '';
            $selfTree = '';

            
            $parent = '\\'.strtolower($k);
            if (isset($display[$parent])) {
                $return .= str_repeat('    ', $level).'* __`'.$k.'`__';
                foreach($display[$parent] as $p) {
                    if ($level == 5) { return; }
                    if (preg_match('/class (\w+)\b/', $p, $r) && $r[1] != 'Exception') {
                        $selfTree .= $this->tree2ul(array($r[1] => array()), $display, $level + 1);
                    }
                }
            } else {
                $return .= str_repeat('    ', $level).'* _`'.$k.'`_';
            }

            if (is_array($v)) {
                $phpTree = PHP_EOL.$this->tree2ul($v, $display, $level + 1);
            }
            
            $return .= $phpTree.$selfTree;
        }
        
        $return = str_replace(' { /**/ } ', '', $return);
        $return = preg_replace('/ extends \\\\?\w+/', '', $return);
        return $return;
    }
    
}

?>