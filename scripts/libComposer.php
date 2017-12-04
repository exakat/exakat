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
function processComponent($vendor, $component, $version, $dir = '') {
    global $sqlite; 
    
    $res = $sqlite->query(<<<SQL
SELECT versions.version AS version, components.id AS id FROM components 
        JOIN versions 
            ON versions.component_id = components.id
        WHERE vendor='$vendor' AND 
              component = '$component'
        ORDER BY versions.version DESC
SQL
);

    $res = $res->fetchArray(SQLITE3_ASSOC);

    if ($res) {
        $componentId = $res['id'];
        print substr("$vendor/$component".str_repeat(' ', 50), 0, 50)."$version / {$res['version']}\n";
//        continue;
    } else {
        $date = time();
        $sqlite->query("INSERT INTO components (vendor, component, last_check) VALUES ('$vendor', '$component', $date);");
        $componentId = $sqlite->lastInsertRowID();
        print "$vendor/$component newly inserted in reference ($componentId)\n";
    }
    
    print "Reading $vendor/$component versions\n";
    $res = shell_exec('composer show '.$vendor.'/'.$component);

    // remove colors
    $res = preg_replace('/\x1B\[([0-9]{1,2}(;[0-9]{1,2})?)?[m|K]/s', '', $res);
    if (!preg_match_all("#versions : (.*?)\n#s", $res, $r)) {
        // Probably an error : ignoring the rest.
        print "Can't read $vendor/$component profile\n ";
        print_r($res);
        
        return;
    }
    $versions = explode(', ', $r[1][0]);

    if ($version == 'latest') {
        foreach($versions as $v) {
            if (preg_match('/^\d+\.\d+\.\d+$/', $v)) {
                break 1;
            }
        }
        // taking the last version used. This way, we always have a default value
        $version = $v;
    }

    $res = shell_exec('composer show '.$vendor.'/'.$component.' "'.$version.'" 2>&1');
    if (!preg_match_all("#versions : (.*?)\n#s", $res, $r)) {
        print 'composer show '.$vendor.'\\'.$component.' "'.$version.'" 2>&1'."\n";
        print_r($res);
        die();
    }
    $solvedVersions = explode(', ', $r[1][0]);
    foreach($solvedVersions as &$sv) {
        if ($sv[0] == 'v') { 
            $sv = substr($sv, 1);
        }
    }
    unset($sv);
    
    foreach($versions as $v) {
        if ($v[0] == 'v') { 
            $v = substr($v, 1);
        }

        $sqlite->query("INSERT OR IGNORE INTO versions (component_id, version) VALUES ($componentId, '$v');");
        if (in_array($v, $solvedVersions)) {
            // needsa a reading 
            $res = $sqlite->query("SELECT id FROM versions WHERE component_id = $componentId AND version= '$v';");
            
            $versionId = $res->fetchArray(SQLITE3_ASSOC)['id'];
        }
    }
    
    if (empty($versionId)) {
        print "Couldn't find versions for '$v'\n";
        print "SELECT id FROM versions WHERE component_id = $componentId AND version= '$v';\n$version requested\n";
        die();
    }

    if ($dir === '') {
        print "Fetching $vendor/$component\n";
        $composer = new stdClass();
        $composer->require = new stdClass();
        $composer->require->{$vendor.'/'.$component} = $version;
        $json = json_encode($composer);
    
        $tmpdir = tempnam(sys_get_temp_dir(), 'exComposer');
        unlink($tmpdir);
        mkdir($tmpdir, 0755);
        file_put_contents($tmpdir.'/composer.json', $json);

        print shell_exec("cd $tmpdir; composer update 2>&1");

        print $tmpdir.'/vendor/'.$vendor.'/'.$component."\n";
    } else {
        $tmpdir = $dir;
        
        print "Reusing dir $tmpdir\n";
    }
    
    $files = recursiveReaddir($tmpdir.'/vendor/'.$vendor.'/'.$component);
    $all = array();
    foreach($files as $file) {
        $all[] = processFile($file);
    }
    $all = call_user_func_array('array_merge_recursive', $all);
    
    print "$vendor/$component / $version ($componentId / $versionId)\n";

    $namespacesIds = array();
    foreach($all as $type => $objects) {
        foreach(array_keys($objects) as $ns) {
            if (!isset($namespacesIds[$ns])) {
                $ns = $sqlite->escapeString($ns);
                $res = $sqlite->query("SELECT id FROM namespaces WHERE version_id = '$versionId' AND namespace = '$ns'");
                $nsid = $res->fetchArray(SQLITE3_ASSOC)['id'];
                if ($nsid) {
                    $namespacesIds[$ns] = $nsid;
                } else {
                    $sqlite->query("INSERT INTO namespaces (version_id, namespace) VALUES ('$versionId', '$ns');");
                    $namespacesIds[$ns] = $sqlite->lastInsertRowID();
                    print "Insertion du namespace '$ns'\n";
                }
            }
        }
    }

    if (isset($all['Class'])) {
        foreach($all['Class'] as $ns => $classes) {
            foreach($classes as $class) {
                // ignore classes with strange characters
                if (preg_match('/[^a-z0-9_\\\\]/i', $class)) { continue; }
                
                $res = $sqlite->query("SELECT id FROM classes WHERE namespace_id = '{$namespacesIds[$ns]}' AND classname = '$class'");
                $nsid = $res->fetchArray(SQLITE3_ASSOC)['id'];
                if (!$nsid) {
                    $sqlite->query("INSERT INTO classes (namespace_id, classname) VALUES ('{$namespacesIds[$ns]}', '$class');");
                    print "Insertion de la classe '$class'\n";
                }
            }
        }
    }

    if (isset($all['Interface'])) {
        foreach($all['Interface'] as $ns => $interfaces) {
            foreach($interfaces as $interface) {
                $res = $sqlite->query("SELECT id FROM interfaces WHERE namespace_id = '{$namespacesIds[$ns]}' AND interfacename = '$interface'");
                $nsid = $res->fetchArray(SQLITE3_ASSOC)['id'];
                if (!$nsid) {
                    $sqlite->query("INSERT INTO interfaces (namespace_id, interfacename) VALUES ('{$namespacesIds[$ns]}', '$interface');");
                }
            }
        }
    }

    if (isset($all['Trait'])) {
        foreach($all['Trait'] as $ns => $traits) {
            foreach($traits as $trait) {
                $res = $sqlite->query("SELECT id FROM traits WHERE namespace_id = '{$namespacesIds[$ns]}' AND traitname = '$trait'");
                $nsid = $res->fetchArray(SQLITE3_ASSOC)['id'];
                if (!$nsid) {
                    $sqlite->query("INSERT INTO traits (namespace_id, traitname) VALUES ('{$namespacesIds[$ns]}', '$trait');");
                }
            }
        }
    }
    
    if ($dir === '' && file_exists($tmpdir.'/composer.lock')) {
        $installed = json_decode(file_get_contents($tmpdir.'/composer.lock'));
        
        if (!isset($installed->packages)) {
            print "$file has a pb with composer.lock\n";
            var_dump($installed);
            die();
        }
        foreach($installed->packages as $package) {
            list($subVendor, $subComponent) = explode('/', $package->name);
            if ($package->version[0] == 'v') {
                $package->version = substr($package->version, 1);
            }
            print " + $subVendor / $subComponent ".$package->version."\n";
            processComponent($subVendor, $subComponent, $package->version, $tmpdir);
        }
    }
}

function recursiveReaddir($tmpdir) {
    if (!file_exists($tmpdir)) { return array(); }
    $dir = opendir($tmpdir);
    $return = array();
    
    $dirs = array();
    while($file = readdir($dir)) {
        if ($file[0] == '.') { continue; }
        
        if (is_dir($tmpdir.'/'.$file)) {
            $dirs[] = recursiveReaddir($tmpdir.'/'.$file);
        } else {
            if (substr($file, -4) != '.php') { continue; }
            $return[] = $tmpdir.'/'.$file;
        }
    }
    if (!empty($dirs)) {
        $return = array_merge($return, array_merge(...$dirs));
    }
    
    return $return;
}

function processFile($file) {
    $tokens = token_get_all(file_get_contents($file));
    
    $return = array('Class'      => array(),
                    'Interface'  => array(),
                    'Trait'      => array(),
                    'Deprecated' => array(),
                    );
    $namespace = 'global';
    $cit = '';
    $deprecated = false;
    
    foreach($tokens as $id => $token) {
        if (is_array($token)) {
            switch($token[0]) {
                case T_DOC_COMMENT : 
                    if (strpos($token[1], '@deprecated') !== false) {
                        $deprecated = true;
                    }
                    break;
                    
                case T_NAMESPACE : 
                    $namespace = '';
                    for ($i = $id + 2; ($tokens[$i] != ';') && ($tokens[$i] != '{') && ($i - $id < 20); $i++) {
                        if (is_array($tokens[$i])) {
                            $namespace .= $tokens[$i][1];
                        } else {
                            $namespace .= $tokens[$i];
                        }
                    }
                    $namespace = trim($namespace);
                    break;

                case T_CONST : 
                    if ($deprecated === true) {
                        $return['Deprecated'][$namespace][] = array('name' => $tokens[$id + 2][1],
                                                                    'cit'  => $cit,
                                                                    'type' => 'const',
                                                                    );
                        $deprecated = false;
                    }
                    break;

                case T_PRIVATE : 
                case T_PUBLIC : 
                case T_PROTECTED : 
                    if ($tokens[$id + 2][0] != T_VARIABLE) { continue; }
                    
                    if ($deprecated === true) {
                        $return['Deprecated'][$namespace][] = array('name' => $tokens[$id + 2][1],
                                                                    'cit'  => $cit,
                                                                    'type' => 'property',
                                                                    );
                        $deprecated = false;
                    }
                    break;

                case T_CLASS : 
                    // skip ::class 
                    if ($tokens[$id - 1][1] == '::') { break 1; }

                    // skip anonymous class : 
                    if (!is_array($tokens[$id + 2])) { break 1; }
                    if ($tokens[$id + 2][0] != T_STRING) { break 1; }

                    $return['Class'][$namespace][] = $tokens[$id + 2][1];
                    $cit = $namespace.'/'.$tokens[$id + 2][1];
                    if ($deprecated === true) {
                        $return['Deprecated'][$namespace][] = array('name' => $tokens[$id + 2][1],
                                                                    'cit'  => '',
                                                                    'type' => 'class',
                                                                    );
                        $deprecated = false;
                    }
                    break;

                case T_FUNCTION : 
                    if ($deprecated === true) {
                        $return['Deprecated'][$namespace][] = array('name' => $tokens[$id + 2][1],
                                                                    'cit'  => $cit,
                                                                    'type' => 'function',
                                                                    );
                        $deprecated = false;
                    }
                    break;

                case T_INTERFACE : 
                    $return['Interface'][$namespace][] = $tokens[$id + 2][1];
                    if ($deprecated === true) {
                        $return['Deprecated'][$namespace][] = array('name' => $tokens[$id + 2][1],
                                                                    'cit'  => '',
                                                                    'type' => 'interface',
                                                                    );
                        $deprecated = false;
                    }
                    break;

                case T_TRAIT : 
                    $return['Trait'][$namespace][] = $tokens[$id + 2][1];
                    if ($deprecated === true) {
                        $return['Deprecated'][$namespace][] = array('name' => $tokens[$id + 2][1],
                                                                    'cit'  => '',
                                                                    'type' => 'trait',
                                                                    );
                        $deprecated = false;
                    }
                    break;
                
                default : 
                    // nothing to do
                
            }
        }
    }
    
    return $return;
}


?>