<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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
use Exakat\Exakat;
use Exakat\Phpexec;
use Exakat\Reports\Helpers\Results;
use Exakat\Vcs\Vcs;

class Manual extends Reports {
    const FILE_EXTENSION = 'md';
    const FILE_FILENAME  = 'manual.exakat';
    
    private $summary = array('Structures'  => array(),
                             'Expressions' => array(),
                             'Values'      => array(),
                             'Empty'       => array(),
                             );

    public function _generate($analyzerList) {
        $md = '';

        $md .= 'Introduction' . PHP_EOL.PHP_EOL;
        
        $md .= '# Structures' . PHP_EOL.PHP_EOL;
        $md .= $this->generateExceptionTree();
        $md .= $this->generateConstants();
        $md .= $this->generateFolders();

        $md .= '# Expressions' . PHP_EOL.PHP_EOL;
        $md .= $this->generateDynamicExpression();

        $md .= '# Values' . PHP_EOL.PHP_EOL;
        $md .= $this->generateErrorMessages();
        $md .= $this->generateRegex();
        $md .= $this->generateIncoming();
        $md .= $this->generateSession();
        $md .= $this->generateSQL();
        $md .= $this->generateURL();
        $md .= $this->generateEmail();
        $md .= $this->generateHash();
        $md .= $this->generateMime();

        $md .= '# Annex' . PHP_EOL.PHP_EOL;
        $md .= $this->generateEmpty();
        $md .= $this->generateSettings();

        $summary = 'Table of content'.PHP_EOL.'---'.PHP_EOL.PHP_EOL;
        
        foreach($this->summary as $section => $list) {
            $summary .= '+ '.$section.PHP_EOL;
            $summary .= '   + '.implode(PHP_EOL.'   + ', $list).PHP_EOL;
        }
        
        $md = $summary.PHP_EOL.'---'.PHP_EOL.$md;
        
        return $md;
    }

    private function generateEmpty() {
        $empty = $this->summary['Empty'];
        $total = count($empty);
        sort($empty);
        unset($this->summary['Empty']);

        $emptyMd = 'The following '.count($empty).' sections didn\'t yield any material. They are noted as empty here.'.PHP_EOL.PHP_EOL.'   + '.implode(PHP_EOL.'   + ', $empty).PHP_EOL;
        
        $this->summary['Annex'][] = '[Empty docs](#empty-docs)';
        $md = '<a name="'.$this->toId('empty-docs').'"></a>'.PHP_EOL.'## Empty docs'.PHP_EOL.PHP_EOL;
        $md .= $emptyMd.PHP_EOL;
    
       return $md;
    }

    private function generateFolders() {
        $folders = '';

        $res = $this->sqlite->query('SELECT * FROM files ORDER BY file');
        $paths = array();
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if (empty($row['file'])) {
                continue;
            }
            $path = dirname($row['file']);
            if (isset($paths[$path])) {
                ++$paths[$path];
            } else {
                $paths[$path] = 1;
            }
        }
        
        ksort($paths);
        $count = count($paths);
        $paths = raiseDimensions($paths);
        $folders .= $this->generateFoldersCB($paths);
        
        $this->summary['Structures'][] = '[Folders](#folders)'.PHP_EOL;
        $md = '<a name="'.$this->toId('folders').'"></a>'.PHP_EOL.'## Folders'.PHP_EOL.PHP_EOL;
        $md .= 'There are '.$count.' folders.'.PHP_EOL.PHP_EOL;
        $md .= $folders.PHP_EOL;
    
       return $md;
    }

    private function generateFoldersCB($array, $level = 0) {
        $return = '';
        
        foreach($array as $key => $value) {
            if (is_array($value)) {
                $return .= str_repeat('  ', $level).'+ `'.$key.'`'.PHP_EOL.
                           $this->generateFoldersCB($value, $level + 1);
            } else {
                $return .= str_repeat('  ', $level).'+ `'.(empty($key) ? '/' : $key).'`'.PHP_EOL;
            }
        }
        
        return $return;
    }
    
    private function generateSettings() {
        $info = array(array('Project name', $this->config->project_name));
        if (!empty($this->config->project_description)) {
            $info[] = array('Code description', $this->config->project_description);
        }
        if (!empty($this->config->project_packagist)) {
            $info[] = array('Packagist', '<a href="https://packagist.org/packages/'.$this->config->project_packagist.'">'.$this->config->project_packagist.'</a>');
        }
        $info = array_merge($info, $this->getVCSInfo());

        $info[] = array('Number of PHP files', $this->datastore->getHash('files'));
        $info[] = array('Number of lines of code', $this->datastore->getHash('loc'));
        $info[] = array('Number of lines of code with comments', $this->datastore->getHash('locTotal'));

        $info[] = array('Report production date', date('r', strtotime('now')));

        $php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});
        $info[] = array('PHP used', $php->getConfiguration('phpversion').' (version '.$this->config->phpversion.' configured)');
        $info[] = array('Ignored files/folders', implode(', ', $this->config->ignore_dirs));

        $info[] = array('Generated by', '[Exakat ](https://www.exakat.io/)');
        $info[] = array('Exakat version', Exakat::VERSION.' ( Build '.Exakat::BUILD.') ');

        $settings = '';
        foreach($info as $i) {
            $settings .= "+ __$i[0]__ : $i[1]\n";
        }
        
        $this->summary['Annex'][] = '[Settings](#exakat-settings)';
        $md = '<a name="'.$this->toId('exakat-settings').'"></a>'.PHP_EOL.'## Settings'.PHP_EOL.PHP_EOL;
        $md .= $settings.PHP_EOL;
    
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
        
        $res = $this->sqlite->query('SELECT cit.name AS class, constants.constant AS constant, value FROM constants 
        join cit on cit.id = constants.citId
        
        ORDER BY cit.name, constants.constant, value');
        
        $previousClass = '';
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            if ($previousClass !== $row['class']) {
                $constants .= '+ `'.$row['class'].'`'.PHP_EOL;
                $previousClass = $row['class'];
            }
            $constants .= '  + `'.$row['constant'].'` = '.$this->escapeMd($row['value']).PHP_EOL;
            ++$total;
        }
        
        if (empty($constants)) {
            return '';
        }
        
        $this->summary['Structures'][] = '[Constants](#constants)';
        $md = '<a name="'.$this->toId('constants').'"></a>'.PHP_EOL.'## Constants'.PHP_EOL.PHP_EOL;
        $md .= $total.' constants and class constants are defined.'.PHP_EOL.PHP_EOL;
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
        $md = '<a name="'.$this->toId('dynamic expressions').'"></a>'.PHP_EOL.'## Dynamic expressions'.PHP_EOL.PHP_EOL;
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

    private function generateSession() {
        return $this->generateGeneric('Php/SessionVariables', 'Session variables');
    }

    private function generateHash() {
        return $this->generateGeneric('Type/Md5String', 'Hash String');
    }

    private function generateMime() {
        return $this->generateGeneric('Type/Mime', 'Mime type');
    }

    private function generateGeneric($analyzer, $name, $section = 'Values') {
        $total = 0;
        $url = '';
        $md = '';
        
        $res = $this->sqlite->query('SELECT * FROM results WHERE analyzer="'.$analyzer.'" ORDER BY fullcode');
        while($row = $res->fetchArray(\SQLITE3_ASSOC)) {
            $url .= '+ `'.$row['fullcode'].'` in '.$this->escapeMd($row['file']).' : '.$this->escapeMd($row['line']).PHP_EOL;
            ++$total;
        }
        
        if (empty($url)) {
            $this->summary['Empty'][] = $name;
            return '';
        }
        
        $id = $this->toId($name);
        $this->summary[$section][] = '['.$name.'](#'.$id.')';
        $md .= '<a name="'.$this->toId($name).'"></a>'.PHP_EOL.'## '.$name.PHP_EOL.PHP_EOL;
        $md .= $total.' '.$name.PHP_EOL.PHP_EOL;
        $md .= $url.PHP_EOL;
    
       return $md;
    }
    
    private function generateRegex() {
        return $this->generateGeneric('Type/Regex', 'Regular expressions');
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
            $parent = $this->toId($r[1]);
            if ($parent[0] != '\\') {
                $parent = '\\'.$parent;
            }

            if (!isset($list[$parent])) {
                $list[$parent] = array();
            }
            
            $list[$parent][] = $row['fullcode'];
        }

        if ($total === 0) {
            $this->summary['Empty'][] = 'Exception Tree';
            return '';
        }
        
        foreach($list as &$l) {
            sort($l);
        }
        
        
        $theTable = $this->tree2ul($exceptions, $list);

        $this->summary['Structures'][] = '[Exception Tree](#exception-tree)';
        $md = '<a name="'.$this->toId('exception-tree').'"></a>'.PHP_EOL.'## Exception Tree'.PHP_EOL.PHP_EOL;
        $md .= $total.' exceptions'.PHP_EOL.PHP_EOL;
        $md .= $theTable.PHP_EOL;
    
       return $md;
    }

    private function toId($name) {
        return str_replace(' ', '-', strtolower($name));
    
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

    protected function getVCSInfo() {
        $info = array();

        $vcsClass = Vcs::getVCS($this->config);
        switch($vcsClass) {
            case 'Git':
                $info[] = array('Git URL', $this->datastore->gethash('vcs_url'));

                $res = $this->datastore->gethash('vcs_branch');
                if (!empty($res)) {
                    $info[] = array('Git branch', trim($res));
                }

                $res = $this->datastore->gethash('vcs_revision');
                if (!empty($res)) {
                    $info[] = array('Git commit', trim($res));
                }
                break 1;

            case 'Svn':
                $info[] = array('SVN URL', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Bazaar':
                $info[] = array('Bazaar URL', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Composer':
                $info[] = array('Package', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Mercurial':
                $info[] = array('Hg URL', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Copy':
                $info[] = array('Original path', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Symlink':
                $info[] = array('Original path', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Tarbz':
                $info[] = array('Source URL', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Cvs':
                $info[] = array('Source URL', $this->datastore->gethash('vcs_url'));
                break 1;

            case 'Targz':
                $info[] = array('Source URL', $this->datastore->gethash('vcs_url'));
                break 1;
            
            default :
                $info[] = array('Repository URL', 'Downloaded archive');
        }
        
        return $info;
    }
    
}

?>