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

use Exakat\Config;
use Exakat\Exakat;

class Upgrade extends Tasks {
    const CONCURENCE = self::ANYTIME;

    public function run(): void {
        // Avoid downloading when it is not a phar
        if ($this->config->is_phar === Config::IS_NOT_PHAR) {
            print 'This can only update a .phar version of exakat. Aborting.' . PHP_EOL;
            return;
        }

        $options = array(
            'http'=>array(
                'method' => 'GET',
                'header' => 'User-Agent: exakat-' . Exakat::VERSION
            )
        );

        $context = stream_context_create($options);
        $html = file_get_contents('https://www.exakat.io/versions/index.php', true, $context);

        if (empty($html)) {
            print 'Unable to reach server to fetch the last version. Try again later.' . PHP_EOL;
            return;
        }

        if (empty($this->config->version)) {
            if (preg_match('/Download exakat version (\d+\.\d+\.\d+) \(Latest\)/s', $html, $r) == 0) {
                print 'Unable to find the requested version. Make sure the version number is valid. ' . PHP_EOL;
                return;
            }

            $version = $r[1];
        } else {
            $version = $this->config->version;
            if (preg_match('/^\d+\.\d+\.\d+$/s', $version, $r) == 0) {
                print 'Version number could not be recognized. Remove the option -version, or provide a valid version number, like "1.8.7".' . PHP_EOL;
                return;
            }

            if (preg_match('/>exakat-' . $version . '.phar<\/a>/s', $html) !== 1) {
                print 'Unable to find last version. Try again later.' . PHP_EOL;
                return;
            }
        }

        if (version_compare(Exakat::VERSION, $version) !== 0) {
            echo 'This version may be updated from the current version ' , Exakat::VERSION , ' to ' , $version  , PHP_EOL;

            if ($this->config->update === true) {

                echo '  Updating to version ' , $version , PHP_EOL;
                preg_match('#<pre id="sha256"><a href="index.php\?file=exakat-' . $version . '.phar.sha256">(.*?)</pre>#', $html, $r);
                $sha256 = strip_tags($r[1]);

                // Read what we can
                $phar = (string) @file_get_contents('https://www.exakat.io/versions/index.php?file=exakat-' . $version . '.phar');

                if (hash('sha256', $phar) !== $sha256) {
                    print 'Error while checking exakat.phar\'s checksum. Aborting update. Please, try again' . PHP_EOL;
                    return;
                }

                $path = sys_get_temp_dir() . '/exakat.1.phar';
                file_put_contents($path, $phar);
                print 'Setting up exakat.phar' . PHP_EOL;
                rename($path, 'exakat.phar');

                return;
            } else {
                print '  You may run this command with -u option to upgrade to the latest exakat version.' . PHP_EOL;
                return;
            }
        } elseif (version_compare(Exakat::VERSION, $r[1]) === 0) {
            print 'This is the latest version (' . Exakat::VERSION . ')' . PHP_EOL;
            return;
        } else {
            print 'This version is ahead of the latest publication (Current : ' . Exakat::VERSION . ', Latest: ' . $r[1] . ')' . PHP_EOL;
            return;
        }
    }
}

?>
