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
                                           '^'       => 'T_XOR',
                                           '|'       => 'T_OR',
                                           '&&'      => 'T_ANDAND',
                                           '||'      => 'T_OROR',
                                           '"'       => 'T_QUOTE',
                                           '"_CLOSE' => 'T_QUOTE_CLOSE',
                                           '$'       => 'T_DOLLAR',
                                           '`'       => 'T_SHELL_QUOTE',
                                           '`_CLOSE' => 'T_SHELL_QUOTE_CLOSE',
                                           '~'       => 'T_TILDE');
    private static $tokens    = array();
    private $config           = null;
    private $isCurrentVersion = false;
    private $version          = null;
    private $actualVersion    = null;
    private $requestedVersion = null;
    private $error            = array();
    
    const VERSIONS         = array('5.2', '5.3', '5.4', '5.5', '5.6', '7.0', '7.1', '7.2', '7.3', '7.4', '8.0',);
    const VERSIONS_COMPACT = array('52',  '53',  '54',  '55',  '56',  '70',  '71',  '72',  '73',  '74',  '80', );

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
                throw new NoPhpBinary('PHP binary for version ' . $this->requestedVersion . ' (' . PHP_BINARY . ') doesn\'t have the right middle version : "' . $this->actualVersion . '". Please, check config/exakat.ini');
            }
        }

        if (empty($pathToBinary)) {
            $this->phpexec = PHP_BINARY;
            // PHP will be always valid if we use the one that is currently executing us
            $this->actualVersion = PHP_VERSION;
        } else {
            $this->phpexec = $pathToBinary;
        }
        
        $this->readConfig();

        if (preg_match('/^php:(.+?)$/', $this->phpexec)) {
            $folder = $pathToBinary;
            $res = shell_exec('docker run -it --rm --name php4exakat -v "$PWD":' . $folder . ' -w ' . $folder . ' ' . $this->phpexec . ' php -v 2>&1');

            if (substr($res, 0, 4) === 'PHP ') {
                $this->phpexec = 'docker run -it --rm --name php4exakat -v "$PWD":' . $folder . ' -w ' . $folder . ' ' . $this->phpexec . ' php ';
            } else {
                throw new NoPhpBinary('Error when accessing Docker\'s PHP : "' . $res . '". Please, check config/exakat.ini');
            }
        } else {
            if (!file_exists($this->phpexec)) {
                throw new NoPhpBinary("PHP binary for version '.$phpversion.' is not valid : '{$this->phpexec}'. Please, check config/exakat.ini");
            }

            if (!is_executable($this->phpexec)) {
                throw new NoPhpBinary('PHP binary for version ' . $phpversion . ' exists but is not executable : "' . $this->phpexec . '". Please, check config/exakat.ini');
            }
        }
    }

    public function getTokens() {
        // prepare the list of tokens
        if ($this->isCurrentVersion === true) {
            $x = get_defined_constants(true);
            unset($x['tokenizer']['TOKEN_PARSE']);
            $tokens = array_flip($x['tokenizer']);
        } else {
            $tmpFile = tempnam(sys_get_temp_dir(), 'Phpexec');
            shell_exec($this->phpexec . ' -r "print \'<?php \\$tokens = \'; \\$x = get_defined_constants(true); if (!isset(\\$x[\'tokenizer\'])) { \\$x[\'tokenizer\'] = array(); }; unset(\\$x[\'tokenizer\'][\'TOKEN_PARSE\']); var_export(array_flip(\\$x[\'tokenizer\'])); print \';  ?>\';" > ' . $tmpFile);
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

    public function getTokenFromFile($file) {
        if ($this->isCurrentVersion === true) {
            $tokens = @token_get_all(file_get_contents($file));
        } else {
            $tmpFile = tempnam(sys_get_temp_dir(), 'Phpexec');
            // -d short_open_tag=1
            $filename = $this->escapeFile($file);
            shell_exec($this->phpexec . '  -r "print \'<?php \\$tokens = \'; \\$code = file_get_contents(' . $filename . '); \\$code = strpos(\\$code, \'<?\') === false ? \'\' : \\$code; var_export(@token_get_all(\\$code)); print \'; ?>\';" > ' . escapeshellarg($tmpFile));
            include $tmpFile;

            unlink($tmpFile);
        }

        // In case the inclusion failed at parsing time
        if (!isset($tokens)) {
            $tokens = array();
        }
        return $tokens;
    }
    
    private function escapeFile($file) {
        return "'" . str_replace(array("'", '"', '$'), array("\\'", '\\"', '\\$'), $file) . "'";
    }

    public function countTokenFromFile($file) {
        // Can't use PHP_SELF, because short_ini_tag can't be changed.
        $filename = $this->escapeFile($file);
        $res = shell_exec($this->phpexec . ' -d short_open_tag=1 -r "print count(@token_get_all(file_get_contents(' . $filename . '))); ?>" 2>&1    ');

        return $res;
    }

    public function getExec() {
        return $this->phpexec;
    }

    public function isValid() {
        if (empty($this->phpexec)) {
            return false;
        }
        $res = shell_exec($this->phpexec . ' -v 2>&1');
        if (preg_match('/^PHP ([0-9\.]+)/', $res, $r)) {
            $this->actualVersion = $r[1];

            if (substr($this->actualVersion, 0, 3) !== $this->requestedVersion) {
                throw new NoPhpBinary('PHP binary for version ' . $this->requestedVersion . ' doesn\'t have the right middle version : "' . $this->actualVersion . '" is provided. Please, check config/exakat.ini');
            }

            return strpos($res, 'The PHP Group') !== false;
        } else {
            return false;
        }
    }

    public function compile($file) {
        $shell = shell_exec($this->phpexec . ' -l ' . escapeshellarg($file) . ' 2>&1');
        $shell = trim($shell);
        
        return !$this->isError(explode("\n", $shell)[0]);
    }

    public function getError() {
        $r = $this->error;
        $this->error = array();
        return $r;
    }
    
    public function isError($resFile) {
        if (substr($resFile, 0, 28) == 'No syntax errors detected in') {
            return false;
            // do nothing. All is fine.
        }

        if (substr($resFile, 0, 15) == 'Errors parsing ') {
            return false; // ignore this one
        }

        if (trim($resFile) == '') {
            return false; // do nothing. All is fine.
        }

        if (preg_match('#^(?:PHP )?Parse error: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            $this->error = array('error' => $r[1],
                                 'file'  => $r[2],
                                 'line'  => $r[3],
                                 );

            return true;
        }

        if (preg_match('#^(?:PHP )?Deprecated: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            return false;
        }
        
        if (preg_match('#^(?:PHP )?Fatal error: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            $this->error = array('error' => $r[1],
                                 'file'  => $r[2],
                                 'line'  => $r[3],
                                 );

            return true;
        }

        // Warnings are considered OK.
        if (preg_match('#^(?:PHP )?Warning: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            return false;
        }

        if (preg_match('#^(?:PHP )?Strict Standards: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            $this->error = array('error' => $r[1],
                                 'file'  => $r[2],
                                 'line'  => $r[3],
                                 );

            return true;
        }

        var_dump($resFile);
        print "\nDon't know that \n";
        die();
        return false;
/*
        if (substr($resFile, 0, 13) == 'Parse error: ') {
            // Actually, almost a repeat of the previous. We just ignore it. (Except in PHP 5.4)
            if (in_array($version, array('52', '70', '71', '72', '73'))) {
                preg_match('#Parse error: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
                $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
                if (isset($incompilables[$fileName])) {
                    continue;

                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 14) == 'PHP Warning:  ') {
            preg_match('#PHP Warning:  (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $file = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$file"])) {
                $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
                $toRemoveFromFiles["/$file"] = 1;
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 18) == 'PHP Fatal error:  ') {
            // Actually, a repeat of the previous. We just ignore it.
            continue;
        } elseif (substr($resFile, 0, 23) == 'PHP Strict standards:  ') {
            preg_match('#PHP Strict standards:  (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $file = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$file"])) {
                $toRemoveFromFiles["/$file"] = 1;
                $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 18) == 'Strict Standards: ') {
            preg_match('#Strict Standards: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$fileName"])) {
                $toRemoveFromFiles["/$fileName"] = 1;
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 18) == 'Strict standards: ') {
            preg_match('#Strict standards: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $file = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$file"])) {
                $toRemoveFromFiles["/$file"] = 1;
                $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 22) == 'PHP Strict Standards: ') {
            preg_match('#PHP Strict Standards: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $file = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$file"])) {
                $toRemoveFromFiles["/$file"] = 1;
                $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 12) == 'Deprecated: ') {
            preg_match('#Deprecated: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $file = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$file"])) {
                $toRemoveFromFiles["/$file"] = 1;
                $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 9) == 'Warning: ') {
            preg_match('#Warning: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$fileName"])) {
                $toRemoveFromFiles["/$fileName"] = 1;
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 8) == 'Notice: ') {
            preg_match('#Notice: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$fileName"])) {
                $toRemoveFromFiles["/$fileName"] = 1;
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 12) == 'PHP Notice: ') {
            preg_match('#PHP Notice: (.+?) in (\./.+?) on line (\d+)#', $resFile, $r);
            $fileName = str_replace("{$this->config->projects_root}/projects/{$dir}/code/", '', $r[2]);
            if (!isset($toRemoveFromFiles["/$fileName"])) {
                $toRemoveFromFiles["/$fileName"] = 1;
                if (isset($incompilables[$fileName])) {
                    continue;
                }
                $incompilables[$fileName] = array('error' => $r[1], 'file' => $fileName, 'line' => $r[3]);
            }
        } elseif (substr($resFile, 0, 14) == 'Errors parsing') {
            continue;
        } elseif (trim($resFile) == 'Could not open input file: {}') {
            display("One path is too long\n");
            continue;
        } else {
            assert(false,  "'".print_r($resFile, true)."'\n");
        }
        */
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
    
    private function readConfig() {
        if ($this->isCurrentVersion === true){
            // this code is also in the ELSE, but we avoid eval here.
            $this->config = array(
                'zend.assertions' => ini_get('zend.assertions'),
                'memory_limit'    => ini_get('memory_limit'),
                'tokenizer'       => extension_loaded('tokenizer'),
                'short_open_tags' => ini_get('short_open_tag'),
                'timezone'        => ini_get('date.timezone'),
                'phpversion'      => PHP_VERSION,
            );
        } else {
            $crc = md5((string) rand(0, 1000000));
            $php = <<<PHP
\\\$results = array(
    'zend.assertions' => ini_get('zend.assertions'),
    'memory_limit'    => ini_get('memory_limit'),
    'tokenizer'       => extension_loaded('tokenizer'),
    'short_open_tags' => ini_get('short_open_tags'),
    'timezone'        => ini_get('date.timezone'),
    'phpversion'      => PHP_VERSION,
    'crc'             => '$crc',
);
echo '\\\$config = '.var_export(\\\$results, true).';';
PHP;
            $res = shell_exec("{$this->phpexec} -r \"$php\" 2>&1");
            if (strpos($res, 'Error') === false ) {
                try {
                    // @ hides potential errors.
                    @eval($res);
                    
                    if ($config['crc'] === $crc) {
                        unset($config['crc']);
                        $this->config = $config;
                    } else {
                        $this->config = array();
                    }
                } catch(\Throwable $e) {
                    $this->config = array();
                }
            } else {
                $this->config = array();
            }
        }
    }
}

?>
