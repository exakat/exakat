<?php
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


namespace Tasks;

class Doctor extends Tasks {

    public function run(\Config $config) {
        $stats = array();

        $stats = array_merge($stats, $this->checkPreRequisite($config));
        $stats = array_merge($stats, $this->checkAutoInstall($config));
        $stats = array_merge($stats, $this->checkOptional($config));

        $doctor = '';
        foreach($stats as $section => $details) {
            $doctor .= "$section : \n";
            foreach($details as $k => $v) {
                $doctor .= '    '.substr("$k                          ", 0, 20).' : '.$v."\n";
            }
            $doctor .= "\n";
        }
        
        return $doctor;
    }

    private function checkPreRequisite($config) {
// Compulsory
        // check for PHP
        $stats['php']['version'] = phpversion();
        $stats['php']['curl']    = extension_loaded('curl')        ? 'Yes' : 'No';
        $stats['php']['sqlite3'] = extension_loaded('sqlite3')     ? 'Yes' : 'No';
        $stats['php']['tokenizer'] = extension_loaded('tokenizer') ? 'Yes' : 'No';

        // java
        $res = shell_exec('java -version 2>&1');
        if (preg_match('/command not found/is', $res)) {
            $stats['java']['installed'] = 'No';
            $stats['java']['installation'] = 'No java found. Please, install Java Runtime (SRE) 1.7 or above from java.com web site.';
        } elseif (preg_match('/java version "(.*)"/is', $res, $r)) {
            list(, $line2,) = explode("\n", $res);
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
        if ($config->neo4j_folder === false) {
            $stats['neo4j']['installed'] = 'Couldn\'t find the path from the config. Please, check it.';
        } elseif (!file_exists($config->neo4j_folder)) {
            $stats['neo4j']['installed'] = 'No (folder : '.$config->neo4j_folder.')';
        } else {
            $file = file($config->neo4j_folder.'/README.txt');
            $stats['neo4j']['version'] = trim($file[0]);

            if (isset($stats['java']['version']) && version_compare($stats['java']['version'], '1.8') < 0) {
                $file = file_get_contents($config->neo4j_folder.'/conf/neo4j-wrapper.conf');
                if (!preg_match('/wrapper.java.additional=-XX:MaxPermSize=(\d+\w)/is', $file, $r)) {
                    $stats['neo4j']['MaxPermSize'] = 'Unset (64M)';
                    $stats['neo4j']['MaxPermSize warning'] = 'Set MaxPermSize to 512 or more in neo4j/conf/neo4j-wrapper.conf, with "wrapper.java.additional=-XX:MaxPermSize=512m" around line 20';
                } else {
                    $stats['neo4j']['MaxPermSize'] = $r[1];
                }
            }

            $file = file_get_contents('neo4j/conf/neo4j-server.properties');
            if (preg_match('/org.neo4j.server.webserver.port=(\d+)/is', $file, $r)) {
                $stats['neo4j']['port'] = $r[1];
            } else {
                $stats['neo4j']['port'] = '7474 (in fact, unset value. Using default : 7474)';
            }

            if ($stats['neo4j']['port'] != $config->neo4j_port) {
                $stats['neo4j']['port_alert'] = $config->neo4j_port.' : configured port in config/config.ini is not the one in the neo4j installation. Please, sync them.';
            }

            if (preg_match('/dbms.security.auth_enabled\s*=\s*false/is', $file, $r)) {
                $stats['neo4j']['authentication'] = 'Not enabled (Please, enable it)';
            } else {
                $stats['neo4j']['authentication'] = 'Enabled.';
                if (empty($config->neo4j_login)) {
                    $stats['neo4j']['login'] = 'Login is not set, but authentication is. Please, set login in config/config.ini';
                } else {
                    if (empty($config->neo4j_password)) {
                        $stats['neo4j']['password'] = 'Login is set, but not password. Please, set it in config/config.ini';
                    }
                }
                $res = gremlin_query('"Hello world"');
                if (isset($res->success) && $res->success === true) {
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
            
            $pidPath = $config->neo4j_folder.'/conf/neo4j-service.pid';
            if (file_exists($pidPath)) {
                $stats['neo4j']['pid'] = file_get_contents($pidPath);
            } else {
                $res = shell_exec('ps aux | grep gremlin | grep plugin');
                preg_match('/^\w+\s+(\d+)\s/is', $res, $r);
                $stats['neo4j']['pid'] = $r[1];
            }
    
            $json = @file_get_contents('http://'.$config->neo4j_host.':'.$config->neo4j_port.'/db/data/');
            if (empty($json)) {
                $stats['neo4j']['running'] = 'No';
            } else {
                $stats['neo4j']['running'] = 'Yes';
                $status = shell_exec('cd '.$config->neo4j_folder.'; ./bin/neo4j status');
                if (strpos($status, 'Neo4j Server is running at pid') !== false) {
                    $stats['neo4j']['running here'] = 'Yes';
                } else {
                    $stats['neo4j']['running here'] = 'No';
                }
                
                if ('{"success":true}' === file_get_contents('http://'.$config->neo4j_host.':'.$config->neo4j_port.'/tp/gremlin/execute')) {
                    $stats['neo4j']['gremlin'] = 'Yes';
                } else {
                    $stats['neo4j']['gremlin'] = 'No';
                    $stats['neo4j']['gremlin-installation'] = 'Install gremlin plugin for neo4j';
                }
            }
        }
        $res = getenv('NEO4J_HOME');
        $stats['neo4j']['$NEO4J_HOME'] = $res;

        // zip
        $res = shell_exec('zip -v');
        if (preg_match('/command not found/is', $res)) {
            $stats['zip']['installed'] = 'No';
        } elseif (preg_match('/Zip\s+([0-9\.]+)/is', $res, $r)) {
            $stats['zip']['installed'] = 'Yes';
            $stats['zip']['version'] = $r[1];
        } else {
            $stats['zip']['error'] = $res;
        }

        return $stats;
    }
    
    private function checkAutoInstall(\Config $config) {
        $stats = array();
        
        // config
        if (!file_exists($config->projects_root.'/config')) {
            $res = mkdir($config->projects_root.'/config', 0755);
        }

        if (!file_exists($config->projects_root.'/config/config.ini')) {
            $version = PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
            $ini = <<<INI
neo4j_host     = '127.0.0.1';
neo4j_port     = '7474';
neo4j_folder   = 'neo4j';
neo4j_login    = 'admin';
neo4j_password = 'admin';


; where and which PHP executable are available
php          = {$_SERVER['_']}

;php52        = /path/to/php53
;php53        = /path/to/php53
;php54        = /path/to/php54
;php55        = /path/to/php55
;php56        = /path/to/php56
;php70        = /path/to/php70
php$version        = {$_SERVER['_']}

INI;
            file_put_contents($config->projects_root.'/config/config.ini', $ini);
        }
        
        if (!file_exists($config->projects_root.'/config/')) {
            $stats['folders']['config-folder'] = 'No';
        } elseif (file_exists($config->projects_root.'/config/config.ini')) {
            $stats['folders']['config-folder'] = 'Yes';
            $stats['folders']['config.ini'] = 'Yes';

            $ini = parse_ini_file('config/config.ini');
        } else {
            $stats['folders']['config-folder'] = 'Yes';
            $stats['folders']['config.ini'] = 'No';
        }

        // projects
        if (file_exists($config->projects_root.'/projects/')) {
            $stats['folders']['projects folder'] = 'Yes';
        } else {
            $res = mkdir($config->projects_root.'/projects/', 0755);
            if (file_exists($config->projects_root.'/projects/')) {
                $stats['folders']['projects folder'] = 'Yes';
            } else {
            $stats['folders']['projects folder'] = 'No';
            }
        }

        $stats['folders']['log'] = file_exists($config->projects_root.'/log/') ? 'Yes' : 'No';
        if ($stats['folders']['log'] == 'No') {
            mkdir($config->projects_root.'/log/', 0755);
            $stats['folders']['log'] = file_exists($config->projects_root.'/projects/log/') ? 'Yes' : 'No';
        }
        $stats['folders']['in'] = file_exists($config->projects_root.'/in/') ? 'Yes' : 'No';
        $stats['folders']['out'] = file_exists($config->projects_root.'/out/') ? 'Yes' : 'No';
        $stats['folders']['test'] = file_exists($config->projects_root.'/projects/test/') ? 'Yes' : 'No';
        $stats['folders']['default'] = file_exists($config->projects_root.'/projects/default/') ? 'Yes' : 'No';
        $stats['folders']['onepage'] = file_exists($config->projects_root.'/projects/onepage/') ? 'Yes' : 'No';

        return $stats;
    }

    private function checkOptional(\Config $config) {
        $stats = array();

        // check PHP 5.2
        if (!$config->php52) {
            $stats['PHP 5.2']['configured'] = 'No';
        } else {
            $version = shell_exec($config->php52.' -r "echo phpversion();" 2>&1');
            if (!preg_match('/5\.2\.[0-9]+/', $version)) {
                $stats['PHP 5.3']['installed'] = 'No';
            } else {
                $stats['PHP 5.2']['installed'] = 'Yes';
                $stats['PHP 5.2']['version'] = $version;
                $stats['PHP 5.2']['short_open_tags'] = shell_exec($config->php52.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.2']['timezone'] = shell_exec($config->php52.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.2']['tokenizer'] = shell_exec($config->php52.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
            }
        }

        // check PHP 5.3
        if (!$config->php53) {
            $stats['PHP 5.3']['configured'] = 'No';
        } else {
            $stats['PHP 5.3']['configured'] = 'Yes';
            $res = trim(shell_exec($config->php53.' -r "echo phpversion();" 2>&1'));
            if (!preg_match('/5\.3\.[0-9]+/', $res)) {
                $stats['PHP 5.3']['installed'] = 'No';
            } else {
                $stats['PHP 5.3']['installed'] = 'Yes';
                $stats['PHP 5.3']['version'] = $res;
                $stats['PHP 5.3']['short_open_tags'] = shell_exec($config->php53.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.3']['timezone'] = shell_exec($config->php53.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.3']['tokenizer'] = shell_exec($config->php53.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
            }
        }
        
        // check PHP 5.4
        if (!$config->php54) {
            $stats['PHP 5.4']['configured'] = 'No';
        } else {
            $stats['PHP 5.4']['configured'] = 'Yes';
            $res = trim(shell_exec($config->php54.' -r "echo phpversion();" 2>&1'));
            if (!preg_match('/5\.4\.[0-9]+/', $res)) {
                $stats['PHP 5.4']['installed'] = 'No';
            } else {
                $stats['PHP 5.4']['installed'] = 'Yes';
                $stats['PHP 5.4']['version'] = $res;
                $stats['PHP 5.4']['short_open_tags'] = shell_exec($config->php54.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.4']['timezone'] = shell_exec($config->php54.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.4']['tokenizer'] = shell_exec($config->php54.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
            }
        }
        
        // check PHP 5.5
        if (!$config->php55) {
            $stats['PHP 5.5']['configured'] = 'No';
        } else {
            $stats['PHP 5.5']['configured'] = 'Yes';
            $res = trim(shell_exec($config->php55.' -r "echo phpversion();" 2>&1'));
            if (!preg_match('/5\.5\.[0-9]+/', $res)) {
                $stats['PHP 5.5']['installed'] = 'No';
            } else {
                $stats['PHP 5.5']['installed'] = 'Yes';
                $stats['PHP 5.5']['version'] = $res;
                $stats['PHP 5.5']['short_open_tags'] = trim(shell_exec($config->php55.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1'));
                $stats['PHP 5.5']['timezone'] = trim(shell_exec($config->php55.' -r "echo ini_get(\'date.timezone\');" 2>&1'));
                $stats['PHP 5.5']['tokenizer'] = shell_exec($config->php55.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
            }
        }
        
        // check PHP 5.6
        if (!$config->php56) {
            $stats['PHP 5.6']['configured'] = 'No';
        } else {
            $stats['PHP 5.6']['configured'] = $config->php56;
            $res = trim(shell_exec($config->php56.' -r "echo phpversion();" 2>&1'));
            if (!preg_match('/5\.6\.[0-9]+/is', $res)) {
                $stats['PHP 5.6']['installed'] = 'No';
            } else {
                $stats['PHP 5.6']['installed'] = 'Yes';
                $stats['PHP 5.6']['version'] = $res;
                $stats['PHP 5.6']['short_open_tags'] = shell_exec($config->php56.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.6']['timezone'] = shell_exec($config->php56.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.6']['tokenizer'] = shell_exec($config->php56.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
            }
        }
        
        // check PHP 7
        if (!$config->php70) {
            $stats['PHP 7.0']['configured'] = 'No';
        } else {
            $stats['PHP 7.0']['configured'] = 'Yes';
            $version = shell_exec($config->php70.' -r "echo phpversion();" 2>&1');
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 7.0']['installed'] = 'No';
            } else {
                $stats['PHP 7.0']['version'] = $version;
                $stats['PHP 7.0']['short_open_tags'] = shell_exec($config->php70.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 7.0']['timezone'] = shell_exec($config->php70.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 7.0']['tokenizer'] = shell_exec($config->php70.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
            }
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

        // composer
        $res = trim(shell_exec('composer about --version'));
        // remove colors from shell syntax
        $res = preg_replace('/\e\[[\d;]*m/', '', $res);
        if (preg_match('/ version ([0-9\.a-z\-]+)/', $res, $r)) {//
            $stats['composer']['installed'] = 'Yes';
            $stats['composer']['version'] = $r[1];
        } else {
            $stats['composer']['installed'] = 'No';
        }

        // wget
        $res = explode("\n", shell_exec('wget -V'))[0];
        if ($res !== '') {//
            $stats['wget']['installed'] = 'Yes';
            $stats['wget']['version'] = $res;
        } else {
            $stats['wget']['installed'] = 'No';
        }

        return $stats;
    }
}

?>
