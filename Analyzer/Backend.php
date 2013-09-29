<?php
/*
   +----------------------------------------------------------------------+
   | Cornac, PHP code inventory                                           |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010 - 2011                                            |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Damien Seguy <damien.seguy@gmail.com>                        |
   +----------------------------------------------------------------------+
 */

class Cornac_Auditeur_Backend {
    private $current = 'tokens';
    private $operations = array();
    private $analyzerName = __CLASS__;
    private $tableIndex = 1;
    private $accessoryIndex = 1;
    private $style = 'report';
    
    static $called = array();
    
    function __construct() {
        $this->reset();
        
        /*
        $x = debug_backtrace();
        if (is_string($x[2]['args'][0])){
            $x = $x[2];
        } else {
            $x = $x[3];
        }
        Cornac_Auditeur_Backend::$called[$x['args'][0]] = "KO";
        */
    }
    
    function __destruct() {
//        print_r(Cornac_Auditeur_Backend::$called);
    }
    
    public function reset() {
        $this->select_file = 'T1.file';
        $this->select_code = 'T1.code';
        $this->select_id = 'T1.id';

        $this->cluster = "''";
        $this->origin = 'T1.element';
        $this->destination = 'T2.element';
        
        $this->operations = array();
        $this->current = 'tokens';
        $this->style = 'report';
        $this->tableIndex = 1;
        $this->accessoryIndex = 1;
    }
    
    function setAnalyzerName($name) {
        $this->analyzerName = $name;
    }

// @tmp this shouldn't stay long. Analyzer will be stripped of its connexion, as backend will do the same
    function setAnalyzer($analyzer) {
        $this->analyzer = $analyzer;
    }
    
    public function type($type) {
        if (is_array($type)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.type IN '.$this->makeIn($type).''));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.type = "'.$type.'"'));
        }

        return $this;
    }

    public function attributes($name, $values = 'Yes') {
        $accessory = $this->getNextAccessoryIndex();
        $this->operations[] = array('table' => "<report_attributes>",
                                    'alias' => $accessory,
                                    'join' => array($this->getCurrentTableIndex().'.id = '.$accessory.'.id'),
                                    'condition' => array($accessory.'.'.$name.' = "'.$values.'"' ));
        return $this;
    }

    public function notType($type) {
        if (is_array($type)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.type NOT IN '.$this->makeIn($type).''));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.type != "'.$type.'"'));
        }

        return $this;
    }

    public function module($module) {
        $this->current = 'report';

        if (is_array($module)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array( $this->getCurrentTableIndex().'.module IN '.$this->makeIn($module).''));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array( $this->getCurrentTableIndex().'.module = "'.$module.'"'));
        }

        return $this;
    }
    
    public function notInReport($module) {
        $index = $this->getNextAccessoryIndex();
        if (is_array($module)) {
            $this->operations[] = array('table' => '<report>',
                                        'alias' => $index,
                                        'left join' => array($index.'.token_id = '.$this->getCurrentTableIndex().'.id',
                                                             $index.'.module IN '.$this->makeIn($module).'',),
                                        'condition' => array($index.'.id IS NULL'));
        } else {
            $this->operations[] = array('table' => '<report>',
                                        'alias' => $index,
                                        'left join' => array($index.'.token_id = '.$this->getCurrentTableIndex().'.id',
                                                             $index.'.module = "'.$module.'"',),
                                        'condition' => array( $index.'.id IS NULL'));
        }

        return $this;
    }

    public function scope($scope = null) {
        if (is_array($scope)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope IN '.$this->makeIn($scope).''));
        } elseif (strpos($scope, '%') !== false) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope LIKE "'.$scope.'"'));
        } elseif (is_null($scope)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope = '.$this->getCurrentTableIndex().'.class'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope = "'.$scope.'"'));
        }
        return $this;    
    }

    public function notScope($scope = null) {
        if (is_array($scope)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope NOT IN '.$this->makeIn($scope).''));
        } elseif (strpos($scope, '%') !== false) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope NOT LIKE "'.$scope.'"'));
        } elseif (is_null($scope)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope != '.$this->getCurrentTableIndex().'.class'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope != "'.$scope.'"'));
        }
        return $this;    
    }
    
    public function notcode($type) {
        if (is_array($type)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.code NOT IN '.$this->makeIn($type).''));
        } elseif (strpos($type, '%') !== false) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.code NOT LIKE "'.$type.'"'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.code != "'.$type.'"'));
        }
        return $this;    
    }

    public function _class($class) {
        if (is_array($class)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.class IN '.$this->makeIn($class).''));
        } elseif (strpos($class, '%') !== false) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.class LIKE "'.$class.'"'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.class = "'.$class.'"'));
        }
        return $this;
    }

    public function notclass($class) {
        if (is_array($class)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.class NOT IN '.$this->makeIn($class).''));
        } elseif (strpos($class, '%') !== false) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.class NOT LIKE "'.$class.'"'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.class != "'.$class.'"'));
        }
        return $this;
    }

    public function sameCode($offset) {
        if ($offset > 0) { // @note absolute notation. 
            if ($offset >= $this->tableIndex) { return $this; }
            // @todo find another way to build the offset table alias
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.code = T'.$offset.'.code'));
        } elseif ($offset < 0) {// @note relative notation. 
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getPreviousTableIndex($offset).'.code = '.$this->getCurrentTableIndex().'.code'));
        } else {
         // @todo nothing. Just ignore
        }
        
        return $this;
    }

    public function sameClass($offset) {
        if ($offset > 0) { // @note absolute notation. 
            if ($offset >= $this->tableIndex) { return $this; }
            // @todo find another way to build the offset table alias
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.class = T'.$offset.'.class'));
        } elseif ($offset < 0) {// @note relative notation. 
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getPreviousTableIndex($offset).'.class = '.$this->getCurrentTableIndex().'.class'));
        } else {
         // @todo nothing. Just ignore
        }
        
        return $this;
    }

    public function sameScope($offset) {
        if ($offset > 0) { // @note absolute notation. 
            if ($offset >= $this->tableIndex) { return $this; }
            // @todo find another way to build the offset table alias
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.scope = T'.$offset.'.scope'));
        } elseif ($offset < 0) {// @note relative notation. 
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getPreviousTableIndex($offset).'.scope = '.$this->getCurrentTableIndex().'.scope'));
        } else {
         // @todo nothing. Just ignore
        }
        
        return $this;
    }

    public function codeRegex($regex, $binary = false) {
        if ($binary) {
            $binary = ' BINARY ';
        } else {
            $binary = '';
        }

        $this->operations[] = array('table' => $this->current,
                                    'alias' => $this->getCurrentTableIndex(),
                                    'condition' => array($binary.$this->getCurrentTableIndex().'.code REGEXP "'.$regex.'"'));
        return $this;
    }    
    public function code($type, $binary = false) {
        if ($binary) {
            $binary = ' BINARY ';
        } else {
            $binary = '';
        }

        if (is_array($type)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($binary.$this->getCurrentTableIndex().'.code IN '.$this->makeIn($type).''));
        } elseif (strpos($type, '%') !== false) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($binary.$this->getCurrentTableIndex().'.code LIKE "'.$type.'"'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($binary.$this->getCurrentTableIndex().'.code = "'.$type.'"'));
        }
        return $this;
    }

    public function getTag($type) {
        $index = $this->getNextTableIndex();
        if (is_array($type)) {
            $this->operations[] = array('table' => '<tokens_tags>',
                                        'alias' => $index,
                                        'join' => array( $this->getCurrentTableIndex().'.token_id = '.$this->getPreviousTableIndex().'.id ',
                                                         $this->getCurrentTableIndex().'.type IN '.$this->makeIn($type).''));
        } else {
            $this->operations[] = array('table' => '<tokens_tags>',
                                        'alias' => $index,
                                        'join' => array( $this->getCurrentTableIndex().'.token_id = '.$this->getPreviousTableIndex().'.id ',
                                                         $this->getCurrentTableIndex().'.type = "'.$type.'"'));
        }
        return $this;
    }

    public function getTaggedToken($type) {
        $init = $this->getCurrentTableIndex();
        $index = $this->getNextTableIndex();
        if (is_array($type)) {
            $this->operations[] = array('table' => '<tokens_tags>',
                                        'alias' => $index,
                                        'join' => array( $index.'.token_id = '.$init.'.id ',
                                                         $index.'.type IN '.$this->makeIn($type).''));
        } else {
            $this->operations[] = array('table' => '<tokens_tags>',
                                        'alias' => $index,
                                        'join' => array( $index.'.token_id = '.$init.'.id ',
                                                         $index.'.type = "'.$type.'"'));
        }

        $index_tokens = $this->getNextTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $index_tokens,
                                    'join' => array( $index.'.token_sub_id = '.$index_tokens.'.id ',
                                                     $init.'.file = '.$index_tokens.'.file'));

        return $this;
    }

    public function getNotTag($type) {
        $index = $this->getNextTableIndex();
        if (is_array($type)) {
            $this->operations[] = array('table' => '<tokens_tags>',
                                        'alias' => $index,
                                        'join' => array( $this->getCurrentTableIndex().'.token_id = '.$this->getPreviousTableIndex().'.id ',
                                                              $this->getCurrentTableIndex().'.type NOT IN '.$this->makeIn($type).''));
        } else {
            $this->operations[] = array('table' => '<tokens_tags>',
                                        'alias' => $index,
                                        'join' => array( $this->getCurrentTableIndex().'.token_id = '.$this->getPreviousTableIndex().'.id ',
                                                              $this->getCurrentTableIndex().'.type != "'.$type.'"'));
        }
        return $this;
    }
    
    public function getParent($level = 1) {
        $level = abs(intval($level));
        
        if ($level == 0) { return $this; }
        
        $index=  $this->getNextTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $index,
                                    'join' => array( $this->getCurrentTableIndex().'.file = '.$this->getPreviousTableIndex().'.file ',
                                                     $this->getPreviousTableIndex().'.left BETWEEN '.$this->getCurrentTableIndex().'.left AND '.$this->getCurrentTableIndex().'.right',
                                                     $this->getCurrentTableIndex().'.level = '.$this->getPreviousTableIndex().'.level - '.$level)
                                    );
        return $this;
    }
    public function element($type) {
        $this->current = 'report';

        if (is_array($type)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.element IN '.$this->makeIn($type).''));
        } elseif ($type == 'is upper') {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array('BINARY UPPER('.$this->getCurrentTableIndex().'.element) = '.$this->getCurrentTableIndex().'.element'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.element = "'.$type.'"'));
        }
        return $this;
    }
/*
    public function extends() {
        $accessory = $this->getNextAccessoryIndex();
        $this->operations[] = array('table' => '<report_dot>',
                                    'alias' => $accessory,
                                    'join' => array($accessory.'.module = "Classes_Hierarchy"', 
                                                    $this->getCurrentTableIndex().'.element '.$accessory.'.a'));
    }
    */

    public function notElement($type) {
        $this->current = 'report';

        if (is_array($type)) {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.element NOT IN '.$this->makeIn($type).''));
        } elseif ($type == 'is upper') {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array('BINARY UPPER('.$this->getCurrentTableIndex().'.element) != '.$this->getCurrentTableIndex().'.element'));
        } else {
            $this->operations[] = array('table' => $this->current,
                                        'alias' => $this->getCurrentTableIndex(),
                                        'condition' => array($this->getCurrentTableIndex().'.element != "'.$type.'"'));
        }
        return $this;
    }
    public function reportCode($col = 'code') {
    // @doc autojoin for cache table
        if ($col == 'cache_code') {
            $previous_table = $this->getCurrentTableIndex();
            $accessory = $this->getNextAccessoryIndex();
            $this->operations[] = array('table' => '<cache>',
                                        'alias' => $accessory,
                                        'join' => array($accessory.'.id = '.$previous_table.'.id'));
            $this->select_code = $accessory.'.code';
    // @doc SQL expression. Interface leak for the moment.
        } elseif (strpos($col, '(') !== false) {
        // @todo no check at all? 
            $this->select_code = $col;
        } else {
        // @doc default behavior : current table's code
            $this->select_code = $this->getCurrentTableIndex().'.'.$col;
        }
        return $this;
    }

    public function reportOrigin($col = 'element') {
        $this->origin = $this->getCurrentTableIndex().'.'.$col;
        return $this;
    }

    public function reportDestination($col = 'element') {
        $this->destination = $this->getCurrentTableIndex().'.'.$col;
        return $this;
    }

    public function reportCluster($col = 'element') {
        $this->cluster = $this->getCurrentTableIndex().'.'.$col;
        return $this;
    }

    public function reportFile($col = 'file') {
        $this->select_file = $this->getCurrentTableIndex().'.'.$col;
        return $this;
    }

    public function reportId($col = 'id') {
        $this->select_id = $this->getCurrentTableIndex().'.'.$col;
        return $this;
    }

    public function nextSibling($diff = 1) {
        $previous_table = $this->getCurrentTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getNextTableIndex(),
                                    'join' => array($this->getCurrentTableIndex().'.file = '.$previous_table.'.file',
                                                    $this->getCurrentTableIndex().'.left = '.$previous_table.'.right + '.$diff));
        return $this;
    }

    public function firstChild($diff = 1) {
        $previous_table = $this->getCurrentTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getNextTableIndex(),
                                    'join' => array($this->getCurrentTableIndex().'.file = '.$previous_table.'.file',
                                                    $this->getCurrentTableIndex().'.left = '.$previous_table.'.left + '.$diff.' '));
        return $this;
    }

    public function lastChild() {
        $previous_table = $this->getCurrentTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getNextTableIndex(),
                                    'join' => array($this->getCurrentTableIndex().'.file = '.$previous_table.'.file',
                                                         $this->getCurrentTableIndex().'.right = '.$previous_table.'.right - 1 '));
        return $this;
    }

    public function inToken() {
        $previous_table = $this->getCurrentTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getNextTableIndex(),
                                    'join' => array($this->getCurrentTableIndex().'.file = '.$previous_table.'.file',
                                                    $this->getCurrentTableIndex().'.left BETWEEN '.$previous_table.'.left AND '.$previous_table.'.right'));
        return $this;
    }

    public function outToken() {
        $previous_table = $this->getCurrentTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getNextTableIndex(),
                                    'join' => array($this->getCurrentTableIndex().'.file = '.$previous_table.'.file',
                                                    $previous_table.'.left BETWEEN '.$this->getCurrentTableIndex().'.left AND '.$this->getCurrentTableIndex().'.right'));
        return $this;
    }

    public function afterToken() {
        $previous_table = $this->getCurrentTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getNextTableIndex(),
                                    'join' => array($this->getCurrentTableIndex().'.file = '.$previous_table.'.file',
                                                    $this->getCurrentTableIndex().'.left > '.$previous_table.'.right'));
        return $this;
    }

    public function beforeToken() {
        $previous_table = $this->getCurrentTableIndex();
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getNextTableIndex(),
                                    'join' => array($this->getCurrentTableIndex().'.file = '.$previous_table.'.file',
                                                    $this->getCurrentTableIndex().'.right < '.$previous_table.'.left'));
        return $this;
    }

    public function hasLevel($diff = 1) {
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getCurrentTableIndex(),
                                    'condition' => array($this->getCurrentTableIndex().'.level = '.$this->getPreviousTableIndex().'.level + '.$diff));
        return $this;
    }

    public function width($diff = '> 1') {
    // @todo check that diff starts with a comparator
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getCurrentTableIndex(),
                                    'condition' => array($this->getCurrentTableIndex().'.right - '.$this->getCurrentTableIndex().'.left '.$diff));
        return $this;
    }

    public function groupby($col) {
        if (is_array($col)) {
            foreach($col as $id => $c) {
                $col[$id] = $this->getCurrentTableIndex().'.'.$c;
            }
        } else {
            $col = array($this->getCurrentTableIndex().'.'.$col);
        }
        $this->operations[] = array('table' => '<tokens>',
                                    'alias' => $this->getCurrentTableIndex(),
                                    'groupby' => $col);
        return $this;
    }

    public function having($col = '') {
    // @todo watch out this incoming argument! 
    // @todo watch out this is PURE SQL!! Bad leakage!
        $this->operations[] = array('table' => $this->current,
                                    'alias' => $this->getCurrentTableIndex(),
                                    'having' => $col);
        return $this;
    }

    public function table($table = "tokens") {
        if (isset($this->operations[0])) {
            $this->operations[0]['table'] = $table;
        } else {
            $this->operations[0] = array('table' => $table,
                                         'alias' => $this->getCurrentTableIndex(),
                                         'condition' => array());
        }
        $this->current = $table;
        return $this;
    }
    
    function intersect($module) {
        $index = $this->getNextTableIndex();
        $this->operations[] = array('table' => '<report>',
                                    'alias' => $index,
                                    'join' => array($index.'.element = '.$this->getCurrentTableIndex().'.element',
                                                    $index.'.module = "'.$module.'"'));
        $this->destination = $index.'.element';
        return $this;
    }

    function excludedFrom($module, $col = 'element') {
        $index = $this->getNextAccessoryIndex();
        $this->operations[] = array('table' => '<report>',
                                    'alias' => $index,
                                    'left join' => array($index.'.element = '.$this->getCurrentTableIndex().'.'.$col,
                                                         $index.'.module = "'.$module.'"'),
                                    'condition' => array($index.'.element IS NULL'));
    }

    function uniqueId($module, $col='id') {
        $index = $this->getNextAccessoryIndex();
        $this->operations[] = array('table' => '<report>',
                                    'alias' => $index,
                                    'left join' => array($index.'.token_id = '.$this->getCurrentTableIndex().'.'.$col,
                                                         $index.'.module = "'.$module.'"'),
                                    'condition' => array($index.'.id IS NULL'));
    }

    public function prepare() {
        $this->select_from = $this->operations[0]['table'];
        $this->select_from_alias = $this->operations[0]['alias'];
        $this->select_where    = array();
        $this->select_joins    = array();
        $this->select_joins_on = array();
        $this->groupby = array();
        $this->having = '';

        foreach($this->operations as $operation) {
            if ($operation['alias'] == $this->select_from_alias) {
                if (isset($operation['condition'])) {
                    $this->select_where = array_merge($this->select_where, $operation['condition']);
                }

                if (isset($operation['groupby'])) {
                    $this->groupby = array_merge($this->groupby, $operation['groupby']);
                }

                if (isset($operation['having'])) {
                    $this->having = 'HAVING '.$operation['having'];
                }
            } else {
                if (isset($operation['join'])) {
                    $this->select_joins[$operation['alias']] = "JOIN ".$operation['table'].' AS '.$operation['alias'];
                    $this->select_joins_on[$operation['alias']] = $operation['join'];
                } elseif (isset($operation['left join'])) {
                    $this->select_joins[$operation['alias']] = "LEFT JOIN ".$operation['table'].' AS '.$operation['alias'];
                    $this->select_joins_on[$operation['alias']] = $operation['left join'];
                } 
                
                if (isset($operation['condition']) && is_array($operation['condition'])) {
                    $this->select_where = array_merge($this->select_where, $operation['condition']);
                } 

                if (isset($operation['groupby'])) {
                    $this->groupby = array_merge($this->groupby, $operation['groupby']);
                }

                if (isset($operation['having'])) {
                    $this->having = 'HAVING '.$operation['having'];
                }
            }
        }

        if (count($this->select_where) > 0) {
            $this->select_where = 'WHERE '.join(" AND\n      ", $this->select_where);
        } else {
            $this->select_where = '';
        }
        
        $joins = array();
        foreach($this->select_joins as $id => $join) {
            $joins[] = $join."\n   ON ".join(" AND\n      ", $this->select_joins_on[$id])."\n";
        }
        $this->select_joins = join("", $joins);
        unset($joins);
        
        if (count($this->groupby) > 0) {
            $this->groupby = 'GROUP BY '.join(', ', $this->groupby);
        } else {
            $this->groupby = '';
        }
        
        $cols = '';
        if ($this->style == 'report') {
            $cols = "NULL, {$this->select_file}, {$this->select_code}, {$this->select_id}, '{$this->analyzerName}', 0";
        } elseif ($this->style == 'attributes') {
            $cols = "{$this->select_id}";
        } elseif ($this->style == 'dot') {
            $cols = "{$this->origin}, {$this->destination}, {$this->cluster}, '{$this->analyzerName}'";
        } else {
            die("Unknown style ($this->style)\n");
        }

        $this->query = <<<SQL
SELECT $cols
FROM <{$this->select_from}> {$this->select_from_alias}
$this->select_joins
$this->select_where
$this->groupby
$this->having
SQL;
    }
    
    public function display($style = 'report') {
        $this->style = $style;

        print_r($this->operations);
        $this->prepare();
        print $this->analyzer->prepareQuery($this->query);
        die();
    }

    public function run($style = 'report') {
        $this->style = $style;
        
        if ($this->analyzerName == "Cornac_Auditeur_Backend") {
            $x = debug_backtrace();
            print $x[2]['args'][0]." has no name (should use setAnalyzerName())! \n";
//            print_r($x);
            die(__METHOD__);
        }

        $this->prepare();
        if ($this->style == 'report') {
            $this->analyzer->execQueryInsert($style, $this->query);
        } elseif ($this->style == 'attributes') {
            $this->analyzer->execQueryAttributes($this->analyzer->name, $this->query);
        } elseif ($this->style == 'dot') {
            $this->analyzer->execQueryInsert('report_dot', $this->query);
        } else {
            die('unknown style "'.$style.'". Aborting\n');
        }
        $this->reset();

/*        $x = debug_backtrace();
        if (is_string($x[2]['args'][0])){
            $x = $x[2];
        } else {
            $x = $x[3];
        }
        Cornac_Auditeur_Backend::$called[$x['args'][0]] = "OK";
*/

        // @todo always true?
        return true;
    }

    private function makeIn($array) {
        return "('".join("', '", $array)."')";
    }
    
    function concat() {
        $args = func_get_args();
        
        global $OPTIONS;
        if (isset($OPTIONS->mysql) && $OPTIONS->mysql['active'] == true) {
            return "CONCAT(".join(",", $args).")";
        } elseif (isset($OPTIONS->sqlite) && $OPTIONS->sqlite['active'] == true) {
            return "".join("||", $args)."";
        } else {
            print "Concat isn't defined for this database!";
            die(__METHOD__);
        }
    }

    function getPreviousTableIndex($offset = 0) {
        return 'T'.($this->tableIndex + $offset - 1);
    }

    function getCurrentTableIndex() {
        return 'T'.$this->tableIndex;
    }
    
    function getNextTableIndex() {
        $this->tableIndex++;
        return 'T'.$this->tableIndex;
    }
    
    function getNextAccessoryIndex() {
        $this->accessoryIndex++;
        return 'S'.$this->accessoryIndex;
    }
}
?>