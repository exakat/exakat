<?php

use Symfony\Component\Finder\Finder;

include_once('./library/Autoload.php');
spl_autoload_register('Autoload::autoload_library');

class RoboFile extends \Robo\Tasks
{
    public function release()
    {
        $this->yell("Releasing Exakat");
    }

    public function versionBump($version = null)
    {
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

    /**
     * check that licence is in the PHP source files
     */
    public function licence()
    {
        $files = Finder::create()->files()->name('*.php')->in('library');
        $files = Finder::create()->files()->name('*')->in('bin');
        $files = Finder::create()->files()->name('*')->in('scripts');
//                             Finder::create()->files()->name('*.php')->in('bin'),
//                             Finder::create()->files()->name('*.php')->in('scripts')
        $docs = [];
        
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
}