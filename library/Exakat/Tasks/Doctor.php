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
    
    public function __construct($gremlin, $config, $subtask = Tasks::IS_NOT_SUBTASK) {
        $this->enabledLog = false;
        parent::__construct($gremlin, $config, $subtask);
    }

    public function run() {
        $stats = array();

        $stats = array_merge($stats, $this->checkPreRequisite());
        $stats = array_merge($stats, $this->checkAutoInstall());
        $stats = array_merge($stats, $this->checkOptional());

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
        $stats['exakat']['version'] = Exakat::VERSION;
        $stats['exakat']['build'] = Exakat::BUILD;

        // check for PHP
        $stats['PHP']['binary']     = $this->config->php;
        $stats['PHP']['executable'] = $this->config->executable;
        $stats['PHP']['version']    = phpversion();
        $stats['PHP']['curl']       = extension_loaded('curl')      ? 'Yes' : 'No (Compulsory, please install it with --with-curl)';
        $stats['PHP']['hash']       = extension_loaded('hash')      ? 'Yes' : 'No (Compulsory, please install it with --enable-hash)';
        $stats['PHP']['phar']       = extension_loaded('phar')      ? 'Yes' : 'No (Needed to run exakat.phar. please install by default)';
        $stats['PHP']['sqlite3']    = extension_loaded('sqlite3')   ? 'Yes' : 'No (Compulsory, please install it by default (remove --without-sqlite3))';
        $stats['PHP']['tokenizer']  = extension_loaded('tokenizer') ? 'Yes' : 'No (Compulsory, please install it by default (remove --disable-tokenizer))';
        $stats['PHP']['mbstring']   = extension_loaded('mbstring')  ? 'Yes' : 'No (Optional, add --enable-mbstring to configure)';
        $stats['PHP']['json']       = extension_loaded('json')      ? 'Yes' : 'No';
        $stats['PHP']['exakat.ini'] = implode(",\n                           ", $this->config->configFiles);

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

            $gremlinPlugin = glob($this->config->neo4j_folder.'/plugins/*/neo4j-gremlin-3.*.jar');
            if (empty($gremlinPlugin)) {
                $stats['neo4j']['gremlinJar'] = 'neo4j-gremlin-3.*.jar coudln\'t be found in the '.$this->config->neo4j_folder.'/plugins/* folders. Make sure gremlin is installed, and running gremlin version 3.* (3.2 is recommended).';
            } elseif (count($gremlinPlugin) > 1) {
                $stats['neo4j']['gremlinJar'] = 'Found '.count($gremlinPlugin).' plugins gremlin. Only one neo4j-gremlin-3.*.jar is sufficient. ';
            } else {
                $stats['neo4j']['gremlinJar'] = basename(trim(array_pop($gremlinPlugin)));
            }

            $stats['neo4j']['scriptFolder'] = file_exists($this->config->neo4j_folder.'/scripts/') ? 'Yes' : 'No';
            if ($stats['neo4j']['scriptFolder'] == 'No') {
                mkdir($this->config->neo4j_folder.'/scripts/', 0755);
                $stats['neo4j']['scriptFolder'] = file_exists($this->config->neo4j_folder.'/scripts/') ? 'Yes' : 'No';
            }
            
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
                
                if ('{"success":true}' === file_get_contents('http://'.$this->config->neo4j_host.':'.$this->config->neo4j_port.'/tp/gremlin/execute')) {
                    $stats['neo4j']['gremlin'] = 'Yes';
                } else {
                    $stats['neo4j']['gremlin'] = 'No';
                    $stats['neo4j']['gremlin-installation'] = 'Install gremlin plugin for neo4j';
                }
            }
            
            $stats['neo4j']['$NEO4J_HOME'] = getenv('NEO4J_HOME');
            $stats['neo4j']['$NEO4J_HOME / config'] = realpath(getenv('NEO4J_HOME')) === realpath($this->config->neo4j_folder) ? 'Same' : 'Different';
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

            $ini = <<<INI
; where and which PHP executable are available
neo4j_host     = '127.0.0.1';
neo4j_port     = '7474';
neo4j_folder   = '$neo4j_folder';
neo4j_login    = 'admin';
neo4j_password = 'admin';

; where and which PHP executable are available
php          = "$php"

;php52        = /path/to/php53
;php53        = /path/to/php53
;php54        = /path/to/php54
;php55        = /path/to/php55
;php56        = /path/to/php56
;php70        = /path/to/php70
;php71        = /path/to/php71
;php72        = /path/to/php72
php$version        = {$_SERVER['_']}

; Default themes to run
#project_themes[] = 'CompatibilityPHP53';
#project_themes[] = 'CompatibilityPHP54';
#project_themes[] = 'CompatibilityPHP55';
#project_themes[] = 'CompatibilityPHP56';
project_themes[] = 'CompatibilityPHP70';
project_themes[] = 'CompatibilityPHP71';
project_themes[] = 'CompatibilityPHP72';
project_themes[] = 'Analyze';
project_themes[] = 'Preferences';
project_themes[] = 'Appinfo';
project_themes[] = 'Appcontent';
project_themes[] = '"Dead code"';
project_themes[] = 'Security';
project_themes[] = 'Custom';

; Default reports to generate
; General reports
project_reports[] = 'Ambassador';
; project_reports[] = 'Text';
; project_reports[] = 'Xml';
; project_reports[] = 'Csv';

; Focused reports
project_reports[] = 'PhpConfiguration';
project_reports[] = 'PhpCompilation';
; project_reports[] = 'Inventories';
; project_reports[] = 'Uml';
; project_reports[] = 'Clustergrammer';

; Special reports
project_reports[] = 'RadwellCode';
project_reports[] = 'ZendFramework';

;Old reports (soon to be dropped)
; project_reports[] = 'Devoops';
; project_reports[] = 'Faceted';

; Limit the size of a project to 1000 k tokens (about 100 k LOC)
token_limit = 1000000


INI;
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

        $stats['folders']['in'] = file_exists($this->config->projects_root.'/in/') ? 'Yes' : 'No';
        $stats['folders']['out'] = file_exists($this->config->projects_root.'/out/') ? 'Yes' : 'No';
        $stats['folders']['projects/test'] = file_exists($this->config->projects_root.'/projects/test/') ? 'Yes' : 'No';
        $stats['folders']['projects/default'] = file_exists($this->config->projects_root.'/projects/default/') ? 'Yes' : 'No';
        $stats['folders']['projects/onepage'] = file_exists($this->config->projects_root.'/projects/onepage/') ? 'Yes' : 'No';

        return $stats;
    }

    private function checkOptional() {
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
            $php = new Phpexec($displayedVersion);
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
            }
        }
        
        return $stats;
    }
}

?>
