<?php

const PIPEFILE = '/tmp/onepageQueue';

$initTime = microtime(true);

$commands = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
unset($commands[0]);
$command = array_shift($commands);

$orders = array('stop', 'init', 'update', 'project', 'onepage', 'report', 'status', 'list', 'stop');

if (!in_array($command, $orders)) {
    serverLog("unknown command : $command");
    die( 'Exakat server (unknown command)');
}

$command($commands);

$endTime = microtime(true);
serverLog(substr($command."\t".floor(1000*($endTime - $initTime))."\t".implode("\t", $commands), 0, 256));
//End script

/// Function definitions

function stop($args) {
    serverLog("Shutting down\n");
    $pid = getmypid();
    echo "<p>Shutting down server (pid : $pid)</p>";
    ob_flush();

    unlink(__FILE__);

    exec('kill '.getmypid());
    // This is killed.
}

function init($args) {
    if (isset($_REQUEST['project'])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $_REQUEST['project']);
        if (empty($project)) {
            $project = '';
        } elseif (file_exists(__DIR__.'/'.$project)) {
            error('Project already exists', $project);
        }
    } else {
        $project = '';
    }
    
    if (isset($_REQUEST['vcs'])) {
        $url = parse_url($_REQUEST['vcs']);
        if (!isset($url['scheme'], $url['host'], $url['path'])) {
            error('Malformed VCS', '');
        }
        
        $vcs = $url['scheme'].'://'.$url['host'].(!empty($url['port']) ? ':'.$url['port'] : '').$url['path'];
        
        if (empty($project)) {
            $project = autoprojectname();
        }

        shell_exec('/usr/bin/php exakat.phar init -p '.$project.' -R '.escapeshellarg($vcs));
    } elseif (isset($_REQUEST['code'])) {
        $php = $_REQUEST['code'];
        if (strpos($php, '<?php') === false) {
            error('Invalide code', '');
        }

        if (empty($project)) {
            $project = autoOnagepageName();
        }
        
        file_put_contents(__DIR__.'/onepage/code/'.$project.'.php', $php);
        shell_exec('/usr/bin/php exakat.phar queue -f '.$project);
    } else {
        error('Missing VCS/code', '');
    }

    echo json_encode(array('project' => $project));
}

function update($args) {
    if (isset($_REQUEST['project'])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $_REQUEST['project']);
        if (empty($project)) {
            error('Missing project', '');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project', $project);
        }
    } else {
        error('No such project', '');
    }

    shell_exec('/usr/bin/php exakat.phar update -p '.$project);
    echo json_encode(array('project' => $project));
}

function project($args) {
    if (isset($args[0])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $args[0]);
        if (empty($project)) {
            error('Missing project', '');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project', '');
        }
    } else {
        error('No such project', '');
    }
    
    echo shell_exec('/usr/bin/php exakat.phar queue -p '.$project);
    echo json_encode(array('project' => $project));
}

function onepage($args) {
    if (isset($args[0])) {
        $file = preg_replace('/[^a-zA-Z0-9-_]/', '', $args[0]);
        if (empty($file)) {
            error('Missing file', '');
        }

        if (!file_exists(__DIR__.'/onepage/code/'.$file.'.php')) {
            error('No such file', '');
        }
    } else {
        error('No such file', '');
    }

    if (!file_exists(__DIR__.'/onepage/reports/'.$file.'.json')) {
        error('No such results', '');
    }
    
    readfile(__DIR__.'/onepage/reports/'.$file.'.json');
}

function report($args) {
    if (isset($args[0])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $args[0]);
        if (empty($project)) {
            error('Missing project', '');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project', $project);
        }
    } else {
        error('No such project', '');
    }
    
    // Check on report, then get dump.sqlite.
    if (!file_exists(__DIR__.'/'.$project.'/report')) {
        error('No report available', $project);
    }
    
    readfile(__DIR__.'/'.$project.'/dump.sqlite');
}

function status($args) {
    global $initTime;
    
    if (isset($args[0]) && !empty($args[0])) {
        if (!file_exists(__DIR__.'/'.$args[0])) {
    	    error('No such project', $args[0]);
	    } elseif (!file_exists(__DIR__.'/'.$args[0].'/code/')) {
            error('No code found', $args[0]);
        } elseif (file_exists(__DIR__.'/'.$args[0].'/')) {
            $json = shell_exec('/usr/bin/php exakat.phar status -p '.$args[0].' -json');
            echo $json;
        } else {
            error('No such project', $args[0]);
        }
    } else {
        $status = array(
            'Status'       => 'OK',
            'Running Time' => duration(microtime(true) - $initTime),
            'Init Time '   => date('r', (int) $initTime),
            'Queue'        => file_exists(PIPEFILE) ? 'Yes' : 'No'
        );
        echo json_encode($status);
    }
}


// Helper functions
function duration($duration) {
    $duration = (int) $duration;
    
    return $duration;
}

function pushToQueue($id) {
    if (!file_exists(PIPEFILE)) {
        echo json_encode(array('status' => 'Server not ready'));
        return;
    }
    
    $fp = fopen(PIPEFILE, 'a');
    if ($fp !== false) {
        fwrite($fp, "$id\n");
        fclose($fp);
    } else {
        echo json_encode(array('status' => 'Could not push to queue'));
        return;
    }
}

function autoProjectName() {
    $files = glob(__DIR__.'/*');
    return 'a'.count($files);
}

function autoOnagepageName() {
    $files = glob(__DIR__.'/onepage/code/*');
    return 'o'.count($files);
}

function error($message, $project) {
    die(json_encode(array('error' => $message,
                          'project' => $project)));
}

function serverLog($message) {
    $fp = fopen(__DIR__.'/server.log', 'a');
    if (!fp !== false) {
        fwrite($fp, date('r')."\t$message\n");
        fclose($fp);
    }
}

?>
