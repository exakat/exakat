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


class Phpexec {
    private $phpexec          = 'php';
    private $tokens           = array(';' => 'T_SEMICOLON',
                                      '=' => 'T_EQUAL',
                                      '+' => 'T_PLUS',
                                      '-' => 'T_MINUS',
                                      '*' => 'T_STAR',
                                      '/' => 'T_SLASH',
                                      '%' => 'T_PERCENTAGE',
                                      '(' => 'T_OPEN_PARENTHESIS',
                                      ')' => 'T_CLOSE_PARENTHESIS',
                                      '!' => 'T_BANG',
                                      '[' => 'T_OPEN_BRACKET',
                                      ']' => 'T_CLOSE_BRACKET',
                                      '{' => 'T_OPEN_CURLY',
                                      '}' => 'T_CLOSE_CURLY',
                                      '.' => 'T_DOT',
                                      ',' => 'T_COMMA',
                                      '@' => 'T_AT',
                                      '?' => 'T_QUESTION',
                                      ':' => 'T_COLON',
                                      '>' => 'T_GREATER',
                                      '<' => 'T_SMALLER',
                                      '&' => 'T_AND',
                                      '^' => 'T_OR',
                                      '|' => 'T_XOR',
                                      '&&' => 'T_ANDAND',
                                      '||' => 'T_OROR',
                                      '"' => 'T_QUOTE',
                                      '"_CLOSE' => 'T_QUOTE_CLOSE',
                                      '$' => 'T_DOLLAR',
                                      '`' => 'T_SHELL_QUOTE',
                                      '`_CLOSE' => 'T_SHELL_QUOTE_CLOSE',
                                      '~' => 'T_TILDE');
    private $config           = array();
    private $isCurrentVersion = false;
    private $isValid          = false;
    
    public function __construct($phpversion) {
        $phpversion3 = substr($phpversion, 0, 3);
        $this->isCurrentVersion = substr(phpversion(), 0, 3) === $phpversion3;
        
        $config = \Config::factory();

        switch($phpversion3) {
            case '5.2' : 
                $this->phpexec = $config->php52;
                if (!empty($this->phpexec)) {
                    $res = shell_exec($this->phpexec.' -v 2>&1');
                    if (substr($res, 0, 4) == 'PHP ') {
                        $this->isValid = true;
                    } else {
                    
                    }
                }
                break 1;

            case '5.3' : 
                $this->phpexec = $config->php53;
                if (!empty($this->phpexec)) {
                    $res = shell_exec($this->phpexec.' -v 2>&1');
                    if (substr($res, 0, 4) == 'PHP ') {
                        $this->isValid = true;
                    } 
                }
                break 1;

            case '5.4' : 
                $this->phpexec = $config->php54;
                if (!empty($this->phpexec)) {
                    $res = shell_exec($this->phpexec.' -v 2>&1');
                    if (substr($res, 0, 4) == 'PHP ') {
                        $this->isValid = true;
                    } 
                }
                break 1;

            case '5.5' : 
                $this->phpexec = $config->php55;
                if (!empty($this->phpexec)) {
                    $res = shell_exec($this->phpexec.' -v 2>&1');
                    if (substr($res, 0, 4) == 'PHP ') {
                        $this->isValid = true;
                    } 
                }
                break 1;

            case '5.6' : 
                $this->phpexec = $config->php56;
                if (!empty($this->phpexec)) {
                    $res = shell_exec($this->phpexec.' -v 2>&1');
                    if (substr($res, 0, 4) == 'PHP ') {
                        $this->isValid = true;
                    } 
                }
                break 1;

            case '7.0' : 
                $this->phpexec = $config->php70;
                if (!empty($this->phpexec)) {
                    $res = shell_exec($this->phpexec.' -v 2>&1');
                    if (substr($res, 0, 4) == 'PHP ') {
                        $this->isValid = true;
                    } 
                }
                break 1;

            default: 
                $this->phpexec = $config->php;
        }
        
        if ($this->isValid) {
            $this->finish();
        }
        
    }

    private function finish() {
        // prepare the configuration for Short tags
        if ($this->isCurrentVersion){
            $shortTags = ini_get('short_open_tag');
        } else {
            $res = shell_exec($this->phpexec.' -i');
            preg_match('/short_open_tag => (\w+) => (\w+)/', $res, $r);
            $shortTags = $r[2] == 'On';
        }
        $this->config['short_open_tag'] = $shortTags ? 'On' : 'Off';

        // prepare the list of tokens
        if ($this->isCurrentVersion) {
            if (!in_array('tokenizer', get_loaded_extensions())) {
                $this->isValid = false;
                return false;
            }
            $x = get_defined_constants(true);
            $tokens = array_flip($x['tokenizer']);
        } else {
            $tmpFile = tempnam('/tmp', 'Phpexec');
            shell_exec($this->phpexec.' -r "print \'<?php \\$tokens = \'; \\$x = get_defined_constants(true); if (!isset(\\$x[\'tokenizer\'])) { \\$x[\'tokenizer\'] = array(); }; var_export(array_flip(\\$x[\'tokenizer\'])); print \';  ?>\';" > '.$tmpFile);
            include $tmpFile;
            unlink($tmpFile);
            if (empty($tokens)) {
                $this->isValid = false;
                return false;
            }
        }
        
        // prepare extra tokens
        $this->tokens += $tokens;
    }
    
    public function getTokenName($token) {
        return $this->tokens[$token];
    }
    
    public function getTokenFromFile($file) {
        if ($this->isCurrentVersion) {
            $tokens = token_get_all(file_get_contents(str_replace('$', '\\\$', $file)));
        } else {
            $tmpFile = tempnam('/tmp', 'Phpexec');
            shell_exec($this->phpexec.'  -d short_open_tag=1  -r "print \'<?php \\$tokens = \'; var_export(token_get_all(file_get_contents(\''.str_replace("\$", "\\\$", $file).'\'))); print \'; ?>\';" > '.$tmpFile);
            include $tmpFile;
            unlink($tmpFile);
        }
        
        return $tokens;
    }

    public function countTokenFromFile($file) {
        if ($this->isCurrentVersion) {
            $res = count(token_get_all(file_get_contents(str_replace('$', '\\\$', $file))));
        } else {
            $res = (int) shell_exec($this->phpexec.' -r "print count(token_get_all(file_get_contents(\''.str_replace("\$", "\\\$", $file).'\'))); ?>" ');
        }
        
        return $res;
    }
    
    public function getExec() {
        return $this->phpexec;
    }

    public function compile($file) {
        $shell = shell_exec($this->phpexec.' -l '.escapeshellarg($file).' 2>&1');
        $shell = preg_replace('/(PHP Warning|Warning|Strict Standards|PHP Warning|PHP Strict Standards|PHP Deprecated|Deprecated): .*?\n/i', '', $shell);
        $shell = trim($shell);

        if (trim($shell) == 'No syntax errors detected in '.$file) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getWhiteCode() {
        return array(
            array_search('T_WHITESPACE', $this->tokens) => 1,
            array_search('T_DOC_COMMENT', $this->tokens) => 1,
            array_search('T_COMMENT', $this->tokens) => 1,
        );
    }

    public function getConfiguration($name = null) {
        if ($name === null) {
            return $this->config;
        } elseif (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return $this->config;
        }
    }
    
    public function isValid() {
        return $this->isValid;
    }
}

?>
