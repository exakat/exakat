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


namespace Exakat\Tasks;

use Exakat\Exakat;
use Exakat\Config;
use Exakat\Task;
use Exakat\Phpexec;

class Doctor extends Tasks {
    const CONCURENCE = self::ANYTIME;

    protected $logname = self::LOG_NONE;

    public function run() {
        $stats = array();

        $stats = array_merge($stats, $this->checkPreRequisite(), $this->checkAutoInstall(), $this->checkPHPs());
        if ($this->config->verbose === true) {
            $stats = array_merge($stats, $this->checkOptional());
        }

        if ($this->config->json === true) {
            print json_encode($stats);
            return;
        }

        $doctor = '';
        foreach($stats as $section => $details) {
            $doctor .= "$section : \n";
            foreach($details as $k => $v) {
                $doctor .= '    '.substr("$k                          ", 0, 20).' : '.$v."\n";
            }
            $doctor .= "\n";
        }
        print $doctor;
    }

    private function checkPreRequisite() {
        // Compulsory
        $stats['exakat']['executable'] = $this->config->executable;
        $stats['exakat']['version']    = Exakat::VERSION;
        $stats['exakat']['build']      = Exakat::BUILD;
        $stats['exakat']['exakat.ini'] = $this->array2list($this->config->configFiles);
        $stats['exakat']['reports']    = $this->array2list($this->config->project_reports);
        $stats['exakat']['themes']     = $this->array2list($this->config->project_themes);

        // check for PHP
        $stats['PHP']['binary']         = phpversion();
        $stats['PHP']['ext/curl']       = extension_loaded('curl')      ? 'Yes' : 'No (Compulsory, please install it with --with-curl)';
        $stats['PHP']['ext/hash']       = extension_loaded('hash')      ? 'Yes' : 'No (Compulsory, please install it with --enable-hash)';
        $stats['PHP']['ext/phar']       = extension_loaded('phar')      ? 'Yes' : 'No (Needed to run exakat.phar. please install by default)';
        $stats['PHP']['ext/sqlite3']    = extension_loaded('sqlite3')   ? 'Yes' : 'No (Compulsory, please install it by default (remove --without-sqlite3))';
        $stats['PHP']['ext/tokenizer']  = extension_loaded('tokenizer') ? 'Yes' : 'No (Compulsory, please install it by default (remove --disable-tokenizer))';
        $stats['PHP']['ext/mbstring']   = extension_loaded('mbstring')  ? 'Yes' : 'No (Optional, add --enable-mbstring to configure)';
        $stats['PHP']['ext/json']       = extension_loaded('json')      ? 'Yes' : 'No';

        // java
        $res = shell_exec('java -version 2>&1');
        if (preg_match('/command not found/is', $res)) {
            $stats['java']['installed'] = 'No';
            $stats['java']['installation'] = 'No java found. Please, install Java Runtime (SRE) 1.7 or above from java.com web site.';
        } elseif (preg_match('/(java|openjdk) version "(.*)"/is', $res, $r)) {
            $lines = explode("\n", $res);
            $line2 = $lines[1];
            $stats['java']['installed'] = 'Yes';
            $stats['java']['type'] = trim($line2);
            $stats['java']['version'] = $r[1];
        } else {
            $stats['java']['error'] = $res;
            $stats['java']['installation'] = 'No java found. Please, install Java Runtime (SRE) 1.7 or above from java.com web site.';
        }
        $res = getenv('JAVA_HOME');
        $stats['java']['$JAVA_HOME'] = $res;

        // neo4j
        if (empty($this->config->neo4j_folder)) {
            $stats['neo4j']['installed'] = 'Couldn\'t find the path to neo4j from the config/exakat.ini. Please, check it.';
        } elseif (!file_exists($this->config->neo4j_folder)) {
            $stats['neo4j']['installed'] = 'No (folder : '.$this->config->neo4j_folder.')';
        } else {
            $file = file($this->config->neo4j_folder.'/README.txt');
            $stats['neo4j']['version'] = trim($file[0]);

            if (isset($stats['java']['version']) && version_compare($stats['java']['version'], '1.8') < 0) {
                $file = file_get_contents($this->config->neo4j_folder.'/conf/neo4j-wrapper.conf');
                if (!preg_match('/wrapper.java.additional=-XX:MaxPermSize=(\d+\w)/is', $file, $r)) {
                    $stats['neo4j']['MaxPermSize'] = 'Unset (64M)';
                    $stats['neo4j']['MaxPermSize warning'] = 'Set MaxPermSize to 512 or more in neo4j/conf/neo4j-wrapper.conf, with "wrapper.java.additional=-XX:MaxPermSize=512m" around line 20';
                } else {
                    $stats['neo4j']['MaxPermSize'] = $r[1];
                }
            }

            $file = file_get_contents($this->config->neo4j_folder.'/conf/neo4j-server.properties');
            if (preg_match('/org.neo4j.server.webserver.port *= *(\d+)/is', $file, $r)) {
                $stats['neo4j']['port'] = $r[1];
            } else {
                $stats['neo4j']['port'] = '7474 (in fact, unset value. Using default : 7474)';
            }

            if ($stats['neo4j']['port'] != $this->config->neo4j_port) {
                $stats['neo4j']['port_alert'] = $this->config->neo4j_port.' : configured port in config/exakat.ini is not the one in the neo4j installation. Please, sync them.';
            }

            if (preg_match('/dbms.security.auth_enabled\s*=\s*false/is', $file, $r)) {
                $stats['neo4j']['authentication'] = 'Not enabled';
            } else {
                $stats['neo4j']['authentication'] = 'Enabled.';
                if (empty($this->config->neo4j_login)) {
                    $stats['neo4j']['login'] = 'Login is not set, but authentication is. Please, set login in config/exakat.ini';
                } elseif (empty($this->config->neo4j_password)) {
                    $stats['neo4j']['password'] = 'Login is set, but not password. Please, set it in config/exakat.ini';
                }
                $res = $this->gremlin->query('"Hello world"');
                if ($res === null) {
                    $stats['neo4j']['credentials'] = 'Server is not running.';
                } elseif (isset($res->success) && $res->success === true) {
                    $stats['neo4j']['credentials'] = 'OK.';
                } else {
                    $stats['neo4j']['credentials'] = 'Login or password are wrong (message : '.$res->errors[0]->message.')';
                }
            }

            if (preg_match('#org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp#is', $file, $r)) {
                $stats['neo4j']['gremlinPlugin'] = 'Configured.';
            } else {
                $stats['neo4j']['gremlinPlugin'] = 'Not found. Make sure that "org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp" is in the conf/neo4j-server.property.';
            }

            $gremlinPlugin = glob($this->config->neo4j_folder.'/plugins/*/neo4j-gremlin-3.2.0-incubating.jar');
            if (empty($gremlinPlugin)) {
                $stats['neo4j']['gremlinJar'] = 'neo4j-gremlin-3.2.0-incubatingcoudln\'t be found in the '.$this->config->neo4j_folder.'/plugins/* folders. Make sure gremlin is installed, and running gremlin version 3.* (3.2.0-incubating is recommended).';
            } elseif (count($gremlinPlugin) > 1) {
                $versions = array();
                foreach($gremlinPlugin as $version) {
                    $v = basename($version);
                    $versions[] = $v;
                }
                $stats['neo4j']['gremlinJar'] = 'Found '.count($gremlinPlugin).' plugins gremlin : '.join(', ', $versions).'. Only one neo4j-gremlin-3.*.jar is sufficient. ';
            } else {
                $stats['neo4j']['gremlinJar'] = basename(trim(array_pop($gremlinPlugin)));
            }

            $stats['neo4j']['scriptFolder'] = file_exists($this->config->neo4j_folder.'/scripts/') ? 'Yes' : 'No';
            if ($stats['neo4j']['scriptFolder'] == 'No') {
                mkdir($this->config->neo4j_folder.'/scripts/', 0755);
                $stats['neo4j']['scriptFolder'] = file_exists($this->config->neo4j_folder.'/scripts/') ? 'Yes' : 'No';
            }

            $context = stream_context_create(array('http'=>
            array(
                'timeout' => 1,
                )
            ));

            $pidPath = $this->config->neo4j_folder.'/conf/neo4j-service.pid';
            if (file_exists($pidPath)) {
                $stats['neo4j']['pid'] = file_get_contents($pidPath);
            } else {
                $res = shell_exec('ps aux | grep gremlin | grep plugin');
                preg_match('/^\w+\s+(\d+)\s/is', $res, $r);
                $stats['neo4j']['pid'] = $r[1];
            }

            $json = @file_get_contents('http://'.$this->config->neo4j_host.':'.$this->config->neo4j_port.'/db/data/');
            if (empty($json)) {
                $stats['neo4j']['running'] = 'No';
            } else {
                $stats['neo4j']['running'] = 'Yes';
                $status = shell_exec('cd '.$this->config->neo4j_folder.'; ./bin/neo4j status');
                if (strpos($status, 'Neo4j Server is running at pid') !== false) {
                    $stats['neo4j']['running here'] = 'Yes';
                } else {
                    $stats['neo4j']['running here'] = 'No';
                }

                if ('{"success":true}' === @file_get_contents('http://'.$this->config->neo4j_host.':'.$this->config->neo4j_port.'/tp/gremlin/execute')) {
                    $stats['neo4j']['gremlin'] = 'Yes';
                } else {
                    $stats['neo4j']['gremlin'] = 'No';
                    $stats['neo4j']['gremlin-installation'] = 'Install gremlin plugin for neo4j';
                }

                $json = file_get_contents('http://'.$this->config->neo4j_host.':'.$this->config->neo4j_port.'/tp/gremlin/execute?script='.urlencode('1 + 1'), false, $context);
                if ($json === '{"success":true,"results":2}') {
                    $stats['neo4j']['gremlin-status'] = 'OK';
                } else {
                    $stats['neo4j']['gremlin-status'] = 'Failed';
                }
            }

            $stats['neo4j']['$NEO4J_HOME'] = getenv('NEO4J_HOME');
            $stats['neo4j']['$NEO4J_HOME / config'] = realpath(getenv('NEO4J_HOME')) === realpath($this->config->neo4j_folder) ? 'Same' : 'Different';
        }

        if ($this->config->project !== 'default') {
            $stats['project']['name']             = $this->config->project_name;
            $stats['project']['url']              = $this->config->project_url;
            $stats['project']['included dirs']    = $this->array2line($this->config->include_dirs);
            $stats['project']['ignored dirs']     = $this->array2line($this->config->ignore_dirs);
            $stats['project']['file extensions']  = $this->array2line($this->config->file_extensions);
            $stats['project']['analyzers']        = $this->array2line($this->config->analyzers);
        }

        return $stats;
    }

    private function checkAutoInstall() {
        $stats = array();

        // config
        if (!file_exists($this->config->projects_root.'/config')) {
            mkdir($this->config->projects_root.'/config', 0755);
        }

        if (!file_exists($this->config->projects_root.'/config/exakat.ini')) {
            $version = PHP_MAJOR_VERSION.PHP_MINOR_VERSION;

            $neo4j_folder = getenv('NEO4J_HOME');
            if (empty($neo4j_folder)) {
                $neo4j_folder = 'neo4j'; // Local Installation
            } elseif (!file_exists($neo4j_folder)) {
                $neo4j_folder = 'neo4j'; // Local Installation
            } elseif (!file_exists($neo4j_folder.'/scripts/')) {
                // This Neo4J has no 'scripts' folder and we use it! Not our database
                $neo4j_folder = 'neo4j'; // Local Installation
            }
            $php = $this->config->php;

            $ini = file_get_contents($this->config->dir_root.'/server/exakat.ini');
            $ini = str_replace(array('{$version}', '{$version_path}'),
                               array( $version,     $_SERVER['_']), 
                               $ini);

            file_put_contents($this->config->projects_root.'/config/exakat.ini', $ini);
        }

        if (!file_exists($this->config->projects_root.'/config/')) {
            $stats['folders']['config-folder'] = 'No';
        } elseif (file_exists($this->config->projects_root.'/config/exakat.ini')) {
            $stats['folders']['config-folder'] = 'Yes';
            $stats['folders']['config.ini'] = 'Yes';

            $ini = parse_ini_file($this->config->projects_root.'/config/exakat.ini');
        } else {
            $stats['folders']['config-folder'] = 'Yes';
            $stats['folders']['config.ini'] = 'No';
        }

        // projects
        if (file_exists($this->config->projects_root.'/projects/')) {
            $stats['folders']['projects folder'] = 'Yes';
        } else {
            mkdir($this->config->projects_root.'/projects/', 0755);
            if (file_exists($this->config->projects_root.'/projects/')) {
                $stats['folders']['projects folder'] = 'Yes';
            } else {
                $stats['folders']['projects folder'] = 'No';
            }
        }

        $stats['folders']['projects/test'] = file_exists($this->config->projects_root.'/projects/test/') ? 'Yes' : 'No';
        $stats['folders']['projects/default'] = file_exists($this->config->projects_root.'/projects/default/') ? 'Yes' : 'No';
        $stats['folders']['projects/onepage'] = file_exists($this->config->projects_root.'/projects/onepage/') ? 'Yes' : 'No';

        return $stats;
    }

    private function checkPHPs() {
        $stats = array();

        // check PHP 5.2
        $stats['PHP 5.2'] = $this->checkPHP($this->config->php52, '5.2');

        // check PHP 5.3
        $stats['PHP 5.3'] = $this->checkPHP($this->config->php53, '5.3');

        // check PHP 5.4
        $stats['PHP 5.4'] = $this->checkPHP($this->config->php54, '5.4');

        // check PHP 5.5
        $stats['PHP 5.5'] = $this->checkPHP($this->config->php55, '5.5');

        // check PHP 5.6
        $stats['PHP 5.6'] = $this->checkPHP($this->config->php56, '5.6');

        // check PHP 7.0
        $stats['PHP 7.0'] = $this->checkPHP($this->config->php70, '7.0');

        // check PHP 7.1
        $stats['PHP 7.1'] = $this->checkPHP($this->config->php71, '7.1');

        // check PHP 7.2
        $stats['PHP 7.2'] = $this->checkPHP($this->config->php72, '7.2');

        return $stats;
    }

    private function checkOptional() {
        $stats = array();

        // git
        $res = trim(shell_exec('git --version 2>&1'));
        if (preg_match('/git version ([0-9\.]+)/', $res, $r)) {//
            $stats['git']['installed'] = 'Yes';
            $stats['git']['version'] = $r[1];
        } else {
            $stats['git']['installed'] = 'No';
            $stats['git']['optional'] = 'Yes';
        }

        // hg
        $res = trim(shell_exec('hg --version 2>&1'));
        if (preg_match('/Mercurial Distributed SCM \(version ([0-9\.]+)\)/', $res, $r)) {//
            $stats['hg']['installed'] = 'Yes';
            $stats['hg']['version'] = $r[1];
        } else {
            $stats['hg']['installed'] = 'No';
            $stats['hg']['optional'] = 'Yes';
        }

        // svn
        $res = trim(shell_exec('svn --version 2>&1'));
        if (preg_match('/svn, version ([0-9\.]+) /', $res, $r)) {//
            $stats['svn']['installed'] = 'Yes';
            $stats['svn']['version'] = $r[1];
        } else {
            $stats['svn']['installed'] = 'No';
            $stats['svn']['optional'] = 'Yes';
        }

        // bazaar
        $res = trim(shell_exec('bzr --version 2>&1'));
        if (preg_match('/Bazaar \(bzr\) ([0-9\.]+) /', $res, $r)) {//
            $stats['bzr']['installed'] = 'Yes';
            $stats['bzr']['version'] = $r[1];
        } else {
            $stats['bzr']['installed'] = 'No';
            $stats['bzr']['optional'] = 'Yes';
        }

        // composer
        $res = trim(shell_exec('composer -V 2>&1'));
        // remove colors from shell syntax
        $res = preg_replace('/\e\[[\d;]*m/', '', $res);
        if (preg_match('/Composer version ([0-9\.a-z@_\(\)\-]+) /', $res, $r)) {//
            $stats['composer']['installed'] = 'Yes';
            $stats['composer']['version'] = $r[1];
        } else {
            $stats['composer']['installed'] = 'No';
        }

        // wget
        $res = explode("\n", shell_exec('wget -V 2>&1'));
        $res = $res[0];
        if ($res !== '') {//
            $stats['wget']['installed'] = 'Yes';
            $stats['wget']['version'] = $res;
        } else {
            $stats['wget']['installed'] = 'No';
        }

        // zip
        $res = shell_exec('zip -v  2>&1');
        if (preg_match('/not found/is', $res)) {
            $stats['zip']['installed'] = 'No';
        } elseif (preg_match('/Zip\s+([0-9\.]+)/is', $res, $r)) {
            $stats['zip']['installed'] = 'Yes';
            $stats['zip']['version'] = $r[1];
        } else {
            $stats['zip']['error'] = $res;
        }

        return $stats;
    }

    private function checkPHP($configVersion, $displayedVersion) {
        $stats = array();

        if (!$configVersion) {
            $stats['configured'] = 'No';
        } else {
            $stats['configured'] = 'Yes ('.$configVersion.')';
            $php = new Phpexec($displayedVersion, $this->config);
            $version = $php->getVersion();
            if (strpos($version, 'not found') !== false) {
                $stats['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['installed'] = 'No';
            } else {
                $stats['version'] = $version;
                if (substr($version, 0, 3) != $displayedVersion) {
                    $stats['version'] = $version.' (This doesn\'t seem to be version '.$displayedVersion.')';
                }
                $stats['short_open_tags'] = $php->getShortTag();
                $stats['timezone']        = $php->getTimezone();
                $stats['tokenizer']       = $php->getTokenizer();
                $stats['memory_limit']    = $php->getMemory_limit();
                $stats['assertions']      = $php->getAssertions();
            }
        }

        return $stats;
    }

    private function array2list($array) {
        return implode(",\n                           ", $array);
    }

    private function array2line($array) {
        return implode(", ", $array);
    }
}
?>
