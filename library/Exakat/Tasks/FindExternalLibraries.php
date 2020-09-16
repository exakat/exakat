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

namespace Exakat\Tasks;

use Exakat\Exceptions\MissingFile;
use Exakat\Exceptions\NoCodeInProject;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\NoSuchProject;

class FindExternalLibraries extends Tasks {
    const CONCURENCE = self::ANYTIME;

    const WHOLE_DIR   = 1;
    const FILE_ONLY   = 2;
    const PARENT_DIR  = 3; // Whole_dir and parent.

    private $php               = null;
    private $phpTokens         = array();
    private $whiteSpace        = array();

    private $classicTestsNames = array();
    private $classicTests      = array();
    private $classic           = array();

    public function __construct(bool $subTask = self::IS_NOT_SUBTASK) {
        parent::__construct($subTask);

        $json = json_decode(file_get_contents("{$this->config->dir_root}/data/externallibraries.json"));
        foreach((array) $json as $name => $o) {
            if ($o->type === 'classic') {
                foreach($o->classes as $class) {
                    $this->classic[$class] = constant("self::$o->ignore");
                }
            } elseif ($o->type === 'test') {
                foreach($o->classes as $class) {
                    $this->classicTests[$class] = constant("self::$o->ignore");
                    $this->classicTestsNames[$class] = $o->name;
                }
            } else {
                assert(false, "[External libraries] : Wrong type for $name : $o->type\n");
            }
        }
    }

    public function run(): void {
        $project = $this->config->project;
        if ($project === 'default') {
            throw new ProjectNeeded();
        }

        if (!file_exists($this->config->project_dir)) {
            throw new NoSuchProject($project);
        }

        if (!file_exists($this->config->code_dir)) {
            throw new NoCodeInProject($project);
        }

        $cacheFile = "{$this->config->project_dir}/config.cache";

        display('Processing files');
        Files::findFiles($this->config->code_dir, $files, $ignoredFiles, $this->config);

        if (empty($files)) {
            display('No files to process. Aborting');
            return;
        }

        $missing = array();
        foreach($files as $file) {
            if (!file_exists($this->config->code_dir . $file)) {
                $missing[] = $file;
            }
        }
        if (!empty($missing)) {
            throw new MissingFile($missing);
        }

        $this->php = exakat('php');

        $this->phpTokens = array_flip($this->php->getTokens());
        $this->whiteSpace = array($this->phpTokens['T_WHITESPACE']  => 1,
                                  $this->phpTokens['T_DOC_COMMENT'] => 1,
                                  $this->phpTokens['T_COMMENT']     => 1,
                                 );

        $r = array();
        rsort($files);
        $ignore = 'None';
        $ignoreLength = 0;
        $regex = '$^(' . implode('|', $this->config->include_dirs) . ')$';
        $toCheckFiles = preg_grep($regex, $files, PREG_GREP_INVERT);

        foreach($toCheckFiles as $file) {
            if (substr($file, 0, $ignoreLength) === $ignore) {
                display( "Ignore $file ($ignore)\n");
                continue;
            }
            $this->process($file);
        }

        if (empty($r)) {
            $newConfigs = array();
        } else {
            $newConfigs = array_merge(...$r);
        }

        $newConfigs = array_diff($newConfigs, $this->config->include_dirs);

        if (count($newConfigs) === 1) {
            display('One external library is going to be omitted : ' . implode(', ', array_keys($newConfigs)));
        } elseif (!empty($newConfigs)) {
            display(count($newConfigs) . ' external libraries are going to be omitted : ' . implode(', ', array_keys($newConfigs)));
        }

        $store = array();
        foreach($newConfigs as $library => $file) {
            $store[] = compact('library', 'file');
        }

        $this->datastore->cleanTable('externallibraries');
        $this->datastore->addRow('externallibraries', $store);

        if ($this->config->update === true) {
            if (file_exists($cacheFile)) {
                display("$project has already a file cache. Omitting.");
                return; //Cancel task
            }

             display("'Updating $project/config.cache");
             $ini = '; This file contains configuration auto-generated by exakat. ' . PHP_EOL .
                    '; Do not edit this file manually : in case of doubt, remove it to regenerate it. ' . PHP_EOL .
                    '; This file was auto-generated on ' . date('r') . PHP_EOL;
            if (empty($newConfigs)) {
                $ini .= PHP_EOL . '; This file is intentionally left blank' . PHP_EOL;
            } else {
                $ini .= PHP_EOL . '; This file is contains ' . count($newConfigs) . ' lines' . PHP_EOL
                               . 'ignore_dirs[] = ' . implode("\n" . 'ignore_dirs[] = ', $newConfigs) . PHP_EOL;
            }

             file_put_contents($cacheFile, $ini);
        } else {
            display('Not updating ' . $project . '/config.cache. ' . count($newConfigs) . ' external libraries found');
        }
    }

    private function process(string $filename): void {
        $return = array();

        $tokens = $this->php->getTokenFromFile($filename);
        if (count($tokens) === 1) {
            return ;
        }
        $this->log->log("$filename : " . count($tokens));

        foreach($tokens as $id => $token) {
            if (is_string($token)) { continue; }

            if (isset($this->whiteSpace[$token[0]])) { continue; }

            // If we find a namespace, it is not the global space, and we may skip the rest.
            if ($token[0] === $this->phpTokens['T_NAMESPACE']) {
                return ;
            }

            if ($token[0] === $this->phpTokens['T_CLASS']) {
                if (!isset($tokens[$id + 2]) ||
                    !is_array($tokens[$id + 2])) { continue; }
                $class = $tokens[$id + 2][1];
                if (!is_string($class)) {
                    // ignoring errors in the parsed code. Should go to log.
                    continue;
                }

                $lclass = strtolower($class);
                $returnPath = '';
                if (isset($this->classic[$lclass])) {
                    if ($this->classic[$lclass] === static::WHOLE_DIR) {
                        $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename));
                    } elseif ($this->classic[$lclass] === static::PARENT_DIR) {
                        $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename), 2);
                    } elseif ($this->classic[$lclass] === static::FILE_ONLY) {
                        $returnPath = preg_replace('#.*projects/.*?/code/#', '/', $filename);
                    }
                    if ($returnPath != '/') {
                        $return[$class] = $returnPath;
                    }
                }

                if (isset($tokens[$id + 4])    &&
                    is_array($tokens[$id + 4]) &&
                    $tokens[$id + 4][0] === $this->phpTokens['T_EXTENDS']) {
                    $ix = $id + 6;
                    $extends = '';

                    while($tokens[$ix][0] === T_NS_SEPARATOR || $tokens[$ix][0] === $this->phpTokens['T_STRING'] ) {
                        $extends .= $tokens[$ix][1];
                        ++$ix;
                    }

                    $extends = strtolower(trim($extends, '\\'));
                    if (isset($this->classicTests[$extends])) {
                        if ($this->classicTests[$extends] === static::WHOLE_DIR) {
                            $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename));
                        } elseif ($this->classicTests[$extends] === static::PARENT_DIR) {
                            $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename), 2);
                        } elseif ($this->classicTests[$extends] === static::FILE_ONLY) {
                            $returnPath = preg_replace('#.*projects/.*?/code/#', '/', $filename);
                        }
                        if ($returnPath !== '/') {
                            $class = $this->classicTestsNames[$extends];
                            $return[$class] = $returnPath;
                        }
                    }
                }
            }
        }
    }
}

?>
