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

namespace Exakat;

use Exakat\Config;
use Exakat\Exceptions\NoPhpBinary;

class Phpexec {
    private $phpexec          = 'php';
    private static $extraTokens    = array(';'       => 'T_SEMICOLON',
                                           '='       => 'T_EQUAL',
                                           '+'       => 'T_PLUS',
                                           '-'       => 'T_MINUS',
                                           '*'       => 'T_STAR',
                                           '/'       => 'T_SLASH',
                                           '%'       => 'T_PERCENTAGE',
                                           '('       => 'T_OPEN_PARENTHESIS',
                                           ')'       => 'T_CLOSE_PARENTHESIS',
                                           '!'       => 'T_BANG',
                                           '['       => 'T_OPEN_BRACKET',
                                           ']'       => 'T_CLOSE_BRACKET',
                                           '{'       => 'T_OPEN_CURLY',
                                           '}'       => 'T_CLOSE_CURLY',
                                           '.'       => 'T_DOT',
                                           ','       => 'T_COMMA',
                                           '@'       => 'T_AT',
                                           '?'       => 'T_QUESTION',
                                           ':'       => 'T_COLON',
                                           '>'       => 'T_GREATER',
                                           '<'       => 'T_SMALLER',
                                           '&'       => 'T_AND',
                                           '^'       => 'T_OR',
                                           '|'       => 'T_XOR',
                                           '&&'      => 'T_ANDAND',
                                           '||'      => 'T_OROR',
                                           '"'       => 'T_QUOTE',
                                           '"_CLOSE' => 'T_QUOTE_CLOSE',
                                           '$'       => 'T_DOLLAR',
                                           '`'       => 'T_SHELL_QUOTE',
                                           '`_CLOSE' => 'T_SHELL_QUOTE_CLOSE',
                                           '~'       => 'T_TILDE');
    private static $tokens    = array();
    private $config           = array();
    private $isCurrentVersion = false;
    private $version          = null;
    private $actualVersion    = null;
    private $requestedVersion = null;

    public function __construct($phpversion = null, $pathToBinary) {
        assert($phpversion !== null, "Can't use null for PHP version");
        $this->requestedVersion = substr($phpversion, 0, 3);

        $this->version = $phpversion;
        $phpversion3 = substr($phpversion, 0, 3);

        $this->isCurrentVersion = substr(PHP_VERSION, 0, 3) === $phpversion3;
        if ($this->isCurrentVersion === true) {
            if (preg_match('/^(\d\.\d+\.\d+)/', PHP_VERSION, $r)) {
                $this->actualVersion = $r[1];
            } else {
                $this->actualVersion = PHP_VERSION;
            }

            if (substr($this->actualVersion, 0, 3) !== $this->requestedVersion) {
                throw new NoPhpBinary('PHP binary for version '.$this->requestedVersion.' ('.$_SERVER['_'].') doesn\'t have the right middle version : "'.$this->actualVersion.'". Please, check config/exakat.ini');
            }
        }

        if (empty($pathToBinary)) {
            $this->phpexec = $_SERVER['_'];
            // PHP will be always valid if we use the one that is currently executing us
            $this->actualVersion = PHP_VERSION;
        } else {
            $this->phpexec = $pathToBinary;
        }

        if (preg_match('/^php:(.+?)$/', $this->phpexec)) {
            $folder = $pathToBinary;
            $res = shell_exec('docker run -it --rm --name php4exakat -v "$PWD":'.$folder.' -w '.$folder.' '.$this->phpexec.' php -v 2>&1');

            if (substr($res, 0, 4) !== 'PHP ') {
                throw new NoPhpBinary('Error when accessing Docker\'s PHP : "'.$res.'". Please, check config/exakat.ini');
            } else {
                $this->phpexec = 'docker run -it --rm --name php4exakat -v "$PWD":'.$folder.' -w '.$folder.' '.$this->phpexec.' php ';
            }
        } else {
            if (!file_exists($this->phpexec)) {
                throw new NoPhpBinary('PHP binary for version '.$phpversion.' is not valid : "'.$this->phpexec.'". Please, check config/exakat.ini');
            }

            if (!is_executable($this->phpexec)) {
                throw new NoPhpBinary('PHP binary for version '.$phpversion.' exists but is not executable : "'.$this->phpexec.'". Please, check config/exakat.ini');
            }
        }
    }

    public function finish() {
        // prepare the configuration for Short tags
        if ($this->isCurrentVersion){
            $shortTags = ini_get('short_open_tag') == '1';
        } else {
            $res = shell_exec($this->phpexec.' -i');
            preg_match('/short_open_tag => (\w+) => (\w+)/', $res, $r);
            $shortTags = $r[2] == 'On';
        }
        $this->config['short_open_tag'] = $shortTags;

        // prepare the configuration for Asp tags
        if ($this->isCurrentVersion){
            $aspTags = ini_get('asp_tags') == '1';
        } else {
            $res = shell_exec($this->phpexec.' -i');
            if (preg_match('/asp_tags => (\w+) => (\w+)/', $res, $r)) {
                $aspTags = $r[2] == 'On';
            } else {
                $aspTags = false;
            }
        }
        $this->config['asp_tags'] = $aspTags;
    }

    public function getTokens() {
        // prepare the list of tokens
        if ($this->isCurrentVersion) {
            $x = get_defined_constants(true);
            $tokens = array_flip($x['tokenizer']);
        } else {
            $tmpFile = tempnam(sys_get_temp_dir(), 'Phpexec');
            shell_exec($this->phpexec.' -r "print \'<?php \\$tokens = \'; \\$x = get_defined_constants(true); if (!isset(\\$x[\'tokenizer\'])) { \\$x[\'tokenizer\'] = array(); }; var_export(array_flip(\\$x[\'tokenizer\'])); print \';  ?>\';" > '.$tmpFile);
            include $tmpFile;
            unlink($tmpFile);
            if (empty($tokens)) {
                return false;
            }
        }

        // prepare extra tokens
        self::$tokens = $tokens + self::$extraTokens;

        return self::$tokens;
    }

    public function getTokenName($token) {
        return self::$tokens[$token];
    }

    public function getTokenValue($token) {
        return array_search($token, self::$tokens);
    }

    public function getTokenFromFile($file) {
        $file = str_replace('$', '\\$', $file);

        if ($this->isCurrentVersion) {
            $tokens = @token_get_all(file_get_contents($file));
        } else {
            $tmpFile = tempnam(sys_get_temp_dir(), 'Phpexec');
            // -d short_open_tag=1
            shell_exec($this->phpexec.'  -r "print \'<?php \\$tokens = \'; \\$code = file_get_contents(\''.$file.'\'); \\$code = strpos(\\$code, \'<?\') === false ? \'\' : \\$code; var_export(@token_get_all(\\$code)); print \'; ?>\';" > '.$tmpFile);
            include $tmpFile;

            unlink($tmpFile);
        }

        // In case the inclusion failed at parsing time
        if (!isset($tokens)) {
            $tokens = array();
        }
        return $tokens;
    }

    public function countTokenFromFile($file) {
        if ($this->isCurrentVersion) {
            $res = count(@token_get_all(file_get_contents(str_replace('$', '\\\$', $file))));
        } else {
            $res = shell_exec($this->phpexec.' -r "print count(@token_get_all(file_get_contents(\''.str_replace("\$", "\\\$", $file).'\'))); ?>" 2>&1    ');
        }

        return $res;
    }

    public function getExec() {
        return $this->phpexec;
    }

    public function isValid() {
        if (empty($this->phpexec)) {
            return false;
        }
        $res = shell_exec($this->phpexec.' -v 2>&1');
        if (preg_match('/PHP ([0-9\.]+)/', $res, $r)) {
            $this->actualVersion = $r[1];

            if (substr($this->actualVersion, 0, 3) !== $this->requestedVersion) {
                throw new NoPhpBinary('PHP binary for version '.$this->requestedVersion.' doesn\'t have the right middle version : "'.$this->actualVersion.'" is provided. Please, check config/exakat.ini');
            }

            return strpos($res, 'The PHP Group') !== false;
        } else {
            return false;
        }
    }

    public function compile($file) {
        $shell = shell_exec($this->phpexec.' -l '.escapeshellarg($file).' 2>&1');
        $shell = preg_replace('/(PHP Warning|Warning|Strict Standards|PHP Warning|PHP Strict Standards|PHP Deprecated|Deprecated): .*?\n/i', '', $shell);
        $shell = trim($shell);

        return trim($shell) == 'No syntax errors detected in '.$file;
    }

    public function getWhiteCode() {
        return array(
            array_search('T_WHITESPACE',  self::$tokens) => 1,
            array_search('T_DOC_COMMENT', self::$tokens) => 1,
            array_search('T_COMMENT',     self::$tokens) => 1,
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

    public function getActualVersion() {
        if ($this->actualVersion === null) {
            $this->isValid();
        }
        return $this->actualVersion;
    }

    public function getVersion() {
        return shell_exec($this->phpexec.' -r "echo phpversion();" 2>&1');
    }

    public function getShortTag() {
        return shell_exec($this->phpexec.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
    }

    public function getTimezone() {
        return shell_exec($this->phpexec.' -r "echo ini_get(\'date.timezone\');" 2>&1');
    }

    public function getTokenizer() {
        return shell_exec($this->phpexec.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
    }

    public function getMemory_limit() {
        return shell_exec($this->phpexec.' -r "echo ini_get(\'memory_limit\');" 2>&1');
    }

    public function getAssertions() {
        return shell_exec($this->phpexec.' -r "echo ini_get(\'zend.assertions\');" 2>&1');
    }
}

?>
