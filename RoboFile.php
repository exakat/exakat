<?php

use Symfony\Component\Finder\Finder;

include './library/Autoload.php';
spl_autoload_register('Autoload::autoload_library');

class RoboFile extends \Robo\Tasks
{
    public function release()
    {
        $this->yell("Releasing Exakat");
    }

    public function versionBump($version = null) {
        if (!$version) {
            $versionParts = explode('.', \Exakat::VERSION);
            $versionParts[count($versionParts)-1]++;
            $version = implode('.', $versionParts);
        }
        $this->taskReplaceInFile(__DIR__.'/library/Exakat.php')
            ->from("VERSION = '".\Exakat::VERSION."'")
            ->to("VERSION = '".$version."'")
            ->run();
    }

    public function updateBuild() {
        $build = \Exakat::BUILD + 1;

        $this->taskReplaceInFile(__DIR__.'/library/Exakat.php')
            ->from("BUILD = '".\Exakat::BUILD."'")
            ->to("BUILD = '".$build."'")
            ->run();
    }

    /**
     * check that licence is in the PHP source files
     */
    public function licence()
    {
        $files = $mit = Finder::create()->files()
                                        ->name('*.php')
                                        ->in('library')
                                        ->in('scripts');
        $docs = array();
        
        $licence = <<<'LICENCE'
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


LICENCE;
        $licenceCRC = crc32(trim($licence));
        
        $id = 0;
        foreach ($files as $file) {
            $id++;
            if (strpos($file, 'Everyman') !== false) { continue; }
            print $file."\n";
            
            $tokens = token_get_all(file_get_contents($file));
            
            $tokenId = 0;
            if ($tokens[$tokenId][0] == T_INLINE_HTML && trim($tokens[$tokenId][1]) == '#!/usr/bin/env php') {
                $tokenId++;
            }
            if ($tokens[$tokenId][0] == T_OPEN_TAG) {
                if ($tokens[$tokenId + 1][0] != T_COMMENT) {
                    array_splice($tokens, $tokenId + 1, 0, array(array(0 => T_COMMENT, 1 => $licence, 2 => 2)));
                    $fp = fopen($file, 'w+');
                    foreach($tokens as $token) {
                        if (is_array($token)) {
                            fwrite($fp, $token[1]);
                        } else {
                            fwrite($fp, $token);
                        }
                    }
                    fclose($fp);
                } else {
                    if (crc32($tokens[$tokenId + 1][1]) != $licenceCRC) {
                        print "Licence seems to be changed in file '$file'\n";
                    }
                }
            } else {
                print "Couldn't apply licence on '$file'\n";
                print_r($tokens[$tokenId]);
            }
        }
    }
    
    /**
     * Bundle everthing for the release
     */
    public function buildRelease()
    {    
        $this->taskExecStack()
         ->stopOnFail()
         ->exec('mkdir release')
         ->exec('mkdir release/config')
         ->exec('mkdir release/bin')
         ->exec('cp -r bin/analyze release/bin/')
         ->exec('cp -r bin/build_root release/bin/')
         ->exec('cp -r bin/export_analyzer release/bin/')
         ->exec('cp -r bin/extract_errors release/bin/')
         ->exec('cp -r bin/files release/bin/')
         ->exec('cp -r bin/load release/bin/')
         ->exec('cp -r bin/log2csv release/bin/')
         ->exec('cp -r bin/magicnumber release/bin/')
         ->exec('cp -r bin/project release/bin/')
         ->exec('cp -r bin/project_init release/bin/')
         ->exec('cp -r bin/report release/bin/')
         ->exec('cp -r bin/report_all release/bin/')
         ->exec('cp -r bin/stat release/bin/')
         ->exec('cp -r bin/tokenizer release/bin/')
         ->exec('cp -r data release/')
         ->exec('cp config/config-default.ini release/config/config-default.ini')
         ->exec('cp -r human release/')
         ->exec('cp -r library release/')
         ->exec('mkdir release/log')
         ->exec('mkdir release/media')
         ->exec('mkdir release/project')
         ->exec('cp -r projects/test release/projects/')
         ->exec('cp -r projects/default release/projects/')
         ->exec('mkdir release/scripts')
         ->exec('cp -r scripts/*.sh release/scripts/')
         ->exec('cp -r scripts/doctor.php release/scripts/')
         ->exec('cp -r tests release/')
         ->exec('cp -r composer.* release/')
         ->exec('cp -r RoboFile.php release/')
         ->exec('tar czf release.tgz release')
         ->exec('mv release.tgz release.'.\Exakat::VERSION.'.tgz')
         ->run();
    }

    /**
     * Clean the build process
     */
    public function clean() {    
        $this->taskExecStack()
         ->stopOnFail()
         ->exec('rm -rf release')
         ->exec('rm -rf release.'.\Exakat::VERSION.'.tgz')
         ->run();
    }
    
    public function pharBuild() {
        $packer = $this->taskPackPhar('exakat.phar')
//                       ->compress()
// compress yield a 'too many files open' error
                       ;
        
        $this->updateBuild();

        $this->taskComposerInstall()
            ->noDev()
            ->printed(false)
            ->run();

        $this->taskComposerInstall()
             ->printed(false)
             ->run();

        $files = Finder::create()->ignoreVCS(true)
            ->files()
            ->path('config/')
            ->path('data/')
            ->path('human/')
            ->path('library/')
            ->path('vendor/')
            ->path('devoops/') 
            ->notPath('batch-import')
            ->notPath('library/Report/Format/Ace')
            ->notPath('projects/')
            ->notPath('media/')
            ->notPath('config.ini')

            ->in(__DIR__)
            ->exclude('neo4j')
            ->exclude('batch-import');
        $this->addFiles($packer, $files);

        $files = Finder::create()->ignoreVCS(true)
                                 ->files()
                                 ->notPath('projects/')
                                 ->notPath('bootstrapvalidator')
                                 ->path('media/devoops/')
                                 ->in(__DIR__);
        $this->addFiles($packer, $files);

        $packer->addFile('exakat','exakat')
               ->executable('exakat')
               ->run();

        $this->taskExecStack()
             ->stopOnFail()
             ->exec('mv exakat.phar ../release/')
             ->run();
    }
    
    private function addFiles($packer, $files) {
        foreach ($files as $file) {
//            print "$file {$file->getRelativePathname()} {$file->getRealPath()}\n";
            print "$file\n";
            $packer->addFile($file->getRelativePathname(), $file->getRealPath());
        }
    }
    
    public function checkFormat() {
        shell_exec('php ~/.composer/vendor/bin/php-cs-fixer fix ./library/Tokenizer --fixers=encoding,eof_ending,elseif,trailing_spaces,indentation');
        shell_exec('php ~/.composer/vendor/bin/php-cs-fixer fix ./library/Analyzer --fixers=encoding,eof_ending,elseif,trailing_spaces,indentation');
        shell_exec('php ~/.composer/vendor/bin/php-cs-fixer fix ./library/Tasks --fixers=encoding,eof_ending,elseif,trailing_spaces,indentation');
    }

    public function checkSyntax() {
        // checking json files
        $files = Finder::create()->ignoreVCS(true)
            ->in('data/')
            ->files()
            ->name('*.json');
        
        $errors = array();
        $total = 0;
        
        foreach($files as $file) {
            $total++;
            $raw = file_get_contents($file);
            $json = json_decode($raw);
            if (empty($json)) {
                $errors[] = "$file is JSON invalid\n";
            }
        }


        // checking inifile files
        $files = Finder::create()->ignoreVCS(true)
            ->in('data/')
            ->files()
            ->name('*.ini');
        
        $errors = array();
        $total = 0;
        
        set_error_handler('error_handler');
        
        foreach($files as $file) {
            $total++;
            $ini = parse_ini_file($file);
            if (empty($ini)) {
                $errors[] = "$file is INI invalid\n";
            }
        }
        set_error_handler(NULL);
        
        // checking sqlite files
        $files = Finder::create()->ignoreVCS(true)
            ->in('data/')
            ->files()
            ->name('*.sqlite');
        
        foreach($files as $file) {
            $total++;
            $sqlite = new sqlite3($file);
            $results = $sqlite->query('pragma integrity_check');
            $response = $results->fetchArray()['integrity_check'];
            if ($response != 'ok') {
                $errors[] = "$file is SQLITE3 invalid (integrity check : $response)\n";
                continue;
            }

            $results = $sqlite->query('PRAGMA foreign_key_check');
            $response = $results->fetchArray()['foreign_key_check'];
            if ($response !== null) {
                $errors[] = "$file is SQLITE3 invalid (foreign key check : $response)\n";
                continue;
            }
        }


        // results
        if (empty($errors)) {
            print "No error found in $total files tested.\n";
        } else {
            echo count($errors).' errors found'."\n";
            print_r($errors);
        }
    }
}

function error_handler ( $errno , $errstr , $errfile = '', $errline = null, $errcontext = array()) {
    print __METHOD__."\n";
    return true;
}

?>