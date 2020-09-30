<?php declare(strict_types = 1);
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
    private $actualVersion    = null;
    private $requestedVersion = null;
    private $error            = array();
    private $version          = '';

    private const CLI_OR_DOCKER_REGEX = '#[a-z0-9]+:[a-z0-9]+#i';

    const VERSIONS         = array('5.2', '5.3', '5.4', '5.5', '5.6', '7.0', '7.1', '7.2', '7.3', '7.4', '8.0', );
    const VERSIONS_COMPACT = array('52',  '53',  '54',  '55',  '56',  '70',  '71',  '72',  '73',  '74',  '80', );

    public function __construct(string $phpversion = null, string $pathToBinary = '') {
        assert($phpversion !== null, "Php version must be a valid version, not $phpversion.");
        assert($pathToBinary !== '', "Path to PHP binary can't be empty for '$phpversion'.");

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
        } elseif (preg_match(self::CLI_OR_DOCKER_REGEX, $pathToBinary)) {
            $res = shell_exec('docker run -it --rm --name php4exakat -v "$PWD":/exakat  -w /exakat ' . $pathToBinary . ' php -v 2>&1') ?? '';
            if (preg_match('/PHP (\d\.\d+\.\d+)/', $res, $r)) {
                $this->actualVersion = $r[1];
            } else {
                $this->actualVersion = 'Error while reading PHP version for ' . $phpversion;
            }
        } else {
            $res = shell_exec("$pathToBinary -v") ?? '';
            if (preg_match('/PHP (\d\.\d+\.\d+)/', $res, $r)) {
                $this->actualVersion = $r[1];
            } else {
                $this->actualVersion = 'Error while reading PHP version for ' . $phpversion;
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

        if (preg_match(self::CLI_OR_DOCKER_REGEX, $this->phpexec)) {
            $res = shell_exec('docker run -it --rm --name php4exakat -v "$PWD":/exakat  -w /exakat ' . $this->phpexec . ' php -v 2>&1') ?? '';

            if (substr($res, 0, 4) !== 'PHP ') {
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

    public function getVersion(): string {
        return $this->version;
    }

    public function getTokens(): array {
        // prepare the list of tokens
        if ($this->isCurrentVersion === true) {
            $x = get_defined_constants(true);
            unset($x['tokenizer']['TOKEN_PARSE']);
            $tokens = array_flip($x['tokenizer']);
        } elseif (preg_match(self::CLI_OR_DOCKER_REGEX, $this->phpexec)) {
            $shell = 'docker run -it --entrypoint /bin/bash --rm ' . $this->phpexec . " -c 'php -r \"\\\$x = get_defined_constants(true);  if (!isset(\\\$x['tokenizer'])) { \\\$x['tokenizer'] = array();  } unset(\\\$x['tokenizer']['TOKEN_PARSE']); var_export(array_flip(\\\$x['tokenizer'])); \"'";
            $res = shell_exec($shell);

            $tmpFile = tempnam(sys_get_temp_dir(), 'Phpexec');
            file_put_contents($tmpFile, "<?php \$tokens = $res; ?>");
            include $tmpFile;
            unlink($tmpFile);
            if (empty($tokens)) {
                return false;
            }
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

    public function getTokenName($token): string {
        return self::$tokens[$token];
    }

    public function getTokenFromFile(string $file): array {
        if ($this->isCurrentVersion === true) {
            $tokens = @token_get_all(file_get_contents($file));
            return $tokens;
        } 
        
        if (preg_match(self::CLI_OR_DOCKER_REGEX, $this->phpexec)) {
            $filename = basename($file);
            $path     = realpath(dirname($file));

            $shell      = "docker run -it -v $path:/exakat -w /exakat --entrypoint /bin/bash --rm {$this->phpexec} -c 'php -r \"\\\$code = file_get_contents(\\\"$filename\\\"); \\\$code = strpos(\\\$code, \\\"<?\\\") === false ? \\\"\\\" : \\\$code; var_export(@token_get_all(\\\$code));\"' ";

            $res = shell_exec($shell);
            try {
                eval("\$tokens = $res;");
            } catch (\Throwable $t) {
                $tokens = array();
            }

            if (empty($tokens)) {
                return array();
            }
            
            return $tokens;
        }

        $tmpFile = tempnam(sys_get_temp_dir(), 'Phpexec');
        // -d short_open_tag=1
        $filename = $this->escapeFile($file);
        shell_exec($this->phpexec . '  -r "print \'<?php \\$tokens = \'; \\$code = file_get_contents(' . $filename . '); \\$code = strpos(\\$code, \'<?\') === false ? \'\' : \\$code; var_export(@token_get_all(\\$code)); print \'; ?>\';" > ' . escapeshellarg($tmpFile));
        include $tmpFile;

        unlink($tmpFile);

        // In case the inclusion failed at parsing time
        if (!isset($tokens)) {
            $tokens = array();
        }
        return $tokens;
    }

    private function escapeFile(string $file): string {
        return "'" . str_replace(array("'", '"', '$'), array("\\'", '\\"', '\\$'), $file) . "'";
    }

    public function countTokenFromFile(string $file): string {
        // Can't use PHP_SELF, because short_ini_tag can't be changed.
        $filename = $this->escapeFile($file);
        if (preg_match(self::CLI_OR_DOCKER_REGEX, $this->phpexec)) {
            $res = shell_exec($this->phpexec . ' -d short_open_tag=1 -r "print count(@token_get_all(file_get_contents(' . $filename . '))); ?>" 2>&1    ') ?? '';
        } else {
            $res = shell_exec($this->phpexec . ' -d short_open_tag=1 -r "print count(@token_get_all(file_get_contents(' . $filename . '))); ?>" 2>&1    ') ?? '';
        }

        return $res;
    }

    public function getExec(): string {
        return $this->phpexec;
    }

    public function isValid(): bool {
        if (empty($this->phpexec)) {
            return false;
        }

        if (preg_match(self::CLI_OR_DOCKER_REGEX, $this->phpexec)) {
            $shell = "docker run -it --rm {$this->phpexec} php -v 2>&1";

            $res = shell_exec($shell) ?? '';
        } else {
            $res = shell_exec("{$this->phpexec} -v 2>&1");
        }

        if (!preg_match('/^PHP ([0-9\.]+)/', $res, $r)) {
            return false;
        }

        $this->actualVersion = $r[1];

        if (substr($this->actualVersion, 0, 3) !== $this->requestedVersion) {
            throw new NoPhpBinary('PHP binary for version ' . $this->requestedVersion . ' doesn\'t have the right middle version : "' . $this->actualVersion . '" is provided. Please, check config/exakat.ini');
        }

        return strpos($res, 'The PHP Group') !== false;
    }

    public function getActualVersion(): string {
        return  $this->actualVersion;
    }

    public function compile(string $file): bool {
        if (preg_match(self::CLI_OR_DOCKER_REGEX, $this->phpexec)) {
            $filename = basename($file);
            $dirname  = realpath(dirname($file));

            $shell = "docker run -v $dirname:/exakat -w /exakat -it --entrypoint /bin/bash --rm {$this->phpexec} -c 'php -l $filename'";
            $res = trim(shell_exec($shell));
       } else {
            $res = shell_exec($this->phpexec . ' -l ' . escapeshellarg($file) . ' 2>&1');
            $res = trim($res);
       }

        foreach(explode("\n", $res) as $r) {
            if (empty($r)) {
                continue;
            }

            if ($this->isError($r)) {
                return false;
            }
        }
        return true;
    }

    public function getError(): array {
        $r = $this->error;
        $this->error = array();
        return $r;
    }

    private function isError(string $resFile): bool {
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

        // Notice are considered OK.
        if (preg_match('#^(?:PHP )?Notice: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            return false;
        }

        if (preg_match('#^(?:PHP )?Strict Standards: (.+?) in (.+?) on line (\d+)#', $resFile, $r)) {
            $this->error = array('error' => $r[1],
                                 'file'  => $r[2],
                                 'line'  => $r[3],
                                 );

            return true;
        }

        display("\nCan't understand this php feedback for $resFile\n");

        return false;
    }

    public function getWhiteCode(): array {
        return array(
            array_search('T_WHITESPACE',  self::$tokens) => 1,
            array_search('T_DOC_COMMENT', self::$tokens) => 1,
            array_search('T_COMMENT',     self::$tokens) => 1,
        );
    }

    public function getConfiguration(string $name = null) {
        if ($name === null) {
            return $this->config;
        } elseif (isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return $this->config;
        }
    }

    private function readConfig(): void {
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
            try {
                $crc = random_int(0, PHP_INT_MAX);
            } catch (\Throwable $t) {
                $crc = (int) microtime(true);
            }

            $php = <<<PHP
\\\$results = array(
    'zend.assertions' => ini_get('zend.assertions'),
    'memory_limit'    => ini_get('memory_limit'),
    'tokenizer'       => extension_loaded('tokenizer'),
    'short_open_tags' => ini_get('short_open_tags'),
    'timezone'        => ini_get('date.timezone'),
    'phpversion'      => PHP_VERSION,
    'crc'             => $crc,
);
echo '\\\$config = '.var_export(\\\$results, true).';';
PHP;
            $readPHPConfig = shell_exec("{$this->phpexec} -r \"$php\" 2>&1") ?? '';
            if (strpos($readPHPConfig, 'Error') === false ) {
                try {
                    // @ hides potential errors.
                    @eval($readPHPConfig);

                    if ($config['crc'] === (int) $crc) {
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

    public function compileFiles(string $project_code, string $tmpFileName, string $script_prefix): void {
        if (preg_match(self::CLI_OR_DOCKER_REGEX, $this->phpexec)) {
            $shell = "docker run -it -v \"{$project_code}\":/exakat -w /exakat/code --entrypoint /bin/bash --rm " . $this->phpexec . " -c 'cat /exakat/.exakat/" . basename($tmpFileName) . ' | sed "s/>/\\\\\\\\>/g" | tr "\n" "\0" | xargs -0 -n1 -P5 -I {} sh -c "php -l {} 2>&1 || true "\'';
        } else {
            copy("{$script_prefix}/server/lint.php", dirname($tmpFileName) . '/lint.php');
            $shell = 'nohup php ' . dirname($tmpFileName) . "/lint.php $this->phpexec {$this->actualVersion[0]}{$this->actualVersion[2]} $project_code $tmpFileName 2>&1 >/dev/null & echo $!";
        }

        shell_exec($shell);
    }
}

?>
