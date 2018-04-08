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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Phpexec;
use Exakat\Tasks\Precedence;
use Exakat\Exceptions\MissingFile;
use Exakat\Exceptions\NoSuchProject;
use Exakat\Exceptions\ProjectNeeded;

class FindExternalLibraries extends Tasks {
    const CONCURENCE = self::ANYTIME;

    const WHOLE_DIR   = 1;
    const FILE_ONLY   = 2;
    const PARENT_DIR  = 3; // Whole_dir and parent.

    private $php = null;
    private $phpTokens = array();

    // classic must be in lower case form.
    private $classic = array('adoconnection'    => self::WHOLE_DIR,
                             'bbq'              => self::WHOLE_DIR,
                             'cpdf'             => self::WHOLE_DIR, // ezpdf
                             'cakeplugin'       => self::PARENT_DIR, // cakephp
                             'dompdf'           => self::PARENT_DIR,
                             'fpdf'             => self::FILE_ONLY,
                             'graph'            => self::PARENT_DIR, // Jpgraph
                             'jpgraph'          => self::PARENT_DIR,
                             'html2pdf'         => self::WHOLE_DIR, // contains tcpdf
                             'htmlpurifier'     => self::FILE_ONLY,
                             'http_class'       => self::WHOLE_DIR,
                             'idna_convert'     => self::WHOLE_DIR,
                             'lessc'            => self::FILE_ONLY,
                             'magpierss'        => self::WHOLE_DIR,
                             'markdown_parser'  => self::FILE_ONLY,
                             'markdown'         => self::WHOLE_DIR,
                             'mpdf'             => self::WHOLE_DIR,
                             'oauthtoken'       => self::WHOLE_DIR,
                             'passwordhash'     => self::FILE_ONLY,
                             'pchart'           => self::WHOLE_DIR,
                             'pclzip'           => self::FILE_ONLY,
                             'gacl'             => self::WHOLE_DIR,
                             'propel'           => self::PARENT_DIR,
                             'gettext_reader'   => self::WHOLE_DIR,
                             'phpexcel'         => self::WHOLE_DIR,
                             'phpmailer'        => self::WHOLE_DIR,
                             'qrcode'           => self::FILE_ONLY,
                             'services_json'    => self::FILE_ONLY,
                             'sfyaml'           => self::WHOLE_DIR,
                             'swift'            => self::PARENT_DIR,
                             'simplepie'        => self::FILE_ONLY,
                             'smarty'           => self::WHOLE_DIR,
                             'tcpdf'            => self::WHOLE_DIR,
                             'text_diff'        => self::WHOLE_DIR,
                             'text_highlighter' => self::WHOLE_DIR,
                             'tfpdf'            => self::WHOLE_DIR,
                             'utf8'             => self::WHOLE_DIR,
                             'ci_xmlrpc'        => self::FILE_ONLY,
                             'xajax'            => self::PARENT_DIR,
                             'yii'              => self::WHOLE_DIR,
                             'zend_view'        => self::WHOLE_DIR,
                             );

    // classic must be in lower case form.
    private $classicTests = array('phpunit_framework_testcase'                          => self::WHOLE_DIR, // PHPunit
                                  'codeception\test\unit'                               => self::WHOLE_DIR, // Codeception
                                  'objectbehavior'                                      => self::WHOLE_DIR, // PHP spec
                                  'unittestcase'                                        => self::WHOLE_DIR, // Simpletest
                                  'atoum'                                               => self::WHOLE_DIR, // Atoum
                                  'drupal\tests\unittestcase'                           => self::WHOLE_DIR, // Drupal
                                  'symfony\bundle\frameworkbundle\test\webtestcase'     => self::WHOLE_DIR, // Symfony
                                  'symfony\bundle\frameworkbundle\test\kerneltestcase'  => self::WHOLE_DIR, // Symfony
                                  // behat, peridot, kahlan, phpt?
                                   );

    public function run() {
        $project = $this->config->project;
        if ($project == 'default') {
            throw new ProjectNeeded();
        }

        if (!file_exists($this->config->projects_root.'/projects/'.$project.'/')) {
            throw new NoSuchProject($project);
        }

        $dir = $this->config->projects_root.'/projects/'.$project.'/code';
        $cacheFile = $this->config->projects_root.'/projects/'.$project.'/config.cache';

        if (file_exists($cacheFile)) {
            display($project.' has already a file cache. Omitting.');
            return; //Cancel task
        }

        display('Processing files');
        $path = $this->config->projects_root.'/projects/'.$project.'/code';
        $files = $this->datastore->getCol('files', 'file');
        if (empty($files)) {
            display('No files to process. Aborting');
            return;
        }

        $missing = array();
        foreach($files as $file) {
            if (!file_exists($path.$file)) {
                $missing[] = $file;
            }
        }
        if (!empty($missing)) {
            throw new MissingFile($missing);
        }

        $this->php = new Phpexec($this->config->phpversion, $this->config->{'php'.str_replace('.', '', $this->config->phpversion)});

        $this->phpTokens = array_flip($this->php->getTokens());

        $r = array();
        rsort($files);
        $ignore = 'None';
        $ignoreLength = 0;
        foreach($files as $id => $file) {
            if (substr($file, 0, $ignoreLength) == $ignore) {
                display( "Ignore $file ($ignore)\n");
                continue;
            }
            $s = $this->process($path.$file);

            if (!empty($s)) {
                $r[] = $s;
                $ignore = array_pop($s);
                $ignoreLength = strlen($ignore);
            }
        }

        if (empty($r)) {
            $newConfigs = array();
        } else {
            $newConfigs = call_user_func_array('array_merge', $r);
        }

        if (count(array_keys($newConfigs)) == 1) {
            display('One external library is going to be omitted : '.implode(', ', array_keys($newConfigs)));
        } elseif (!empty($newConfigs)) {
            display(count(array_keys($newConfigs)).' external libraries are going to be omitted : '.implode(', ', array_keys($newConfigs)));
        }

        $store = array();
        foreach($newConfigs as $library => $file) {
            $store[] = array('library' => $library,
                             'file'    => $file);
        }

        $this->datastore->cleanTable('externallibraries');
        $this->datastore->addRow('externallibraries', $store);

        if ($this->config->update === true) {
             display('Updating '.$project.'/config.cache');
             $ini = '; This file contains configuration auto-generated by exakat. '                    .PHP_EOL.
                    '; Do not edit this file manually : in case of doubt, remove it to regenerate it. '.PHP_EOL.
                    '; This file was auto-generated on '.date('r')                                     .PHP_EOL;
            if (empty($newConfigs)) {
                $ini .= PHP_EOL.'; This file is intentionally left blank'.PHP_EOL;
            } else {
                $ini .= PHP_EOL.'; This file is contains '.count($newConfigs).' lines'.PHP_EOL
                               .'ignore_dirs[] = '.implode("\n".'ignore_dirs[] = ', $newConfigs).PHP_EOL;
            }

             file_put_contents($cacheFile, $ini);
        } else {
            display('Not updating '.$project.'/config.cache. '.count($newConfigs).' external libraries found');
        }
    }

    private function process($filename) {
        $return = array();

        $tokens = $this->php->getTokenFromFile($filename);
        if (count($tokens) == 1) {
            return $return;
        }
        $this->log->log("$filename : ".count($tokens));

        foreach($tokens as $id => $token) {
            if (is_string($token)) { continue; }

            if ($token[0] === $this->phpTokens['T_WHITESPACE'])  { continue; }
            if ($token[0] === $this->phpTokens['T_DOC_COMMENT']) { continue; }
            if ($token[0] === $this->phpTokens['T_COMMENT'])     { continue; }

            // If we find a namespace, it is not the global space, and we may skip the rest.
            if ($token[0] == $this->phpTokens['T_NAMESPACE']) {
                return;
            }

            if ($token[0] == $this->phpTokens['T_CLASS']) {
                if (!is_array($tokens[$id + 2])) { continue; }
                $class = $tokens[$id + 2][1];
                if (!is_string($class)) {
                    // ignoring errors in the parsed code. Should go to log.
                    continue;
                }

                $lclass = strtolower($class);
                if (isset($this->classic[$lclass])) {
                    $returnPath = '';
                    if ($this->classic[$lclass] == static::WHOLE_DIR) {
                        $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename));
                    } elseif ($this->classic[$lclass] == static::PARENT_DIR) {
                        $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename), 2);
                    } elseif ($this->classic[$lclass] == static::FILE_ONLY) {
                        $returnPath = preg_replace('#.*projects/.*?/code/#', '/', $filename);
                    }
                    if ($returnPath != '/') {
                        $return[$class] = $returnPath;
                    }
                }

                if (is_array($tokens[$id + 4]) && $tokens[$id + 4][0] == $this->phpTokens['T_EXTENDS']) {
                    $ix = $id + 6;
                    $extends = '';

                    while($tokens[$ix][0] == T_NS_SEPARATOR || $tokens[$ix][0] == $this->phpTokens['T_STRING'] ) {
                        $extends .= $tokens[$ix][1];
                        ++$ix;
                    }

                    $extends = trim(strtolower($extends), '\\');
                    if (isset($this->classicTests[$extends])) {
                        if ($this->classicTests[$extends] == static::WHOLE_DIR) {
                            $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename));
                        } elseif ($this->classicTests[$extends] == static::PARENT_DIR) {
                            $returnPath = dirname(preg_replace('#.*projects/.*?/code/#', '/', $filename), 2);
                        } elseif ($this->classicTests[$extends] == static::FILE_ONLY) {
                            $returnPath = preg_replace('#.*projects/.*?/code/#', '/', $filename);
                        }
                        if ($returnPath != '/') {
                            $return[$class] = $returnPath;
                        }
                    }
                }
            }
        }

        return $return;
    }
}

?>
