<?php

const PIPEFILE = '/tmp/onepageQueue';

$initTime = microtime(true);

$commands = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
unset($commands[0]);
$command = array_shift($commands);

$orders = array('stop', 'init', 'update', 'project', 'onepage', 'report', 'status', 'list', 'stop');

if (!in_array($command, $orders)) {
    serverLog("unknown command : $command");
    echo "Exakat server (unknown command)";
    exit;
}

$command($commands);

$endTime = microtime(true);
serverLog(substr($command."\t".floor(1000*($endTime - $initTime))."\t".implode("\t", $commands), 0, 256));
//End script

/// Function definitions

function stop($path) {
    serverLog("Shutting down\n");
    $pid = getmypid();
    echo "<p>Shutting down server (pid : $pid)</p>";
    ob_flush();

    unlink(__FILE__);

    exec('kill '.getmypid());
    // This is killed.
}

function init($path) {
    if (isset($_REQUEST['project'])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $_REQUEST['project']);
        if (empty($project)) {
            $project = '';
        } elseif (file_exists(__DIR__.'/'.$project)) {
            error('Project already exists');
        }
    } else {
        $project = '';
    }
    
    if (isset($_REQUEST['vcs'])) {
        $url = parse_url($_REQUEST['vcs']);
        if (!isset($url['scheme'], $url['host'], $url['path'])) {
            error('Malformed VCS');
        }
        $vcs = $url['scheme'].'://'.$url['host'].(!empty($url['port']) ? ':'.$url['port'] : '').$url['path'];
        
        if (empty($project)) {
            $project = autoprojectname();
        }

        shell_exec('__PHP__ __EXAKAT__ init -p '.$project.' -R '.escapeshellarg($_REQUEST['vcs']));
    } elseif (isset($_REQUEST['code'])) {
        $php = $_REQUEST['code'];
        if (strpos($php, '<?php') === false) {
            error('Invalide code');
        }

        if (empty($project)) {
            $project = autoOnagepageName();
        }
        
        file_put_contents(__DIR__.'/onepage/code/'.$project.'.php', $php);
        shell_exec('__PHP__ __EXAKAT__ queue -f '.$project);
    } else {
        error('Missing VCS/code');
    }

    echo json_encode(array('project' => $project));
}

function update($path) {
    if (isset($_REQUEST['project'])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $_REQUEST['project']);
        if (empty($project)) {
            error('Missing project');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project');
        }
    } else {
        error('No such project');
    }

    shell_exec('__PHP__ __EXAKAT__ update -p '.$project);
    echo json_encode(array('project' => $project));
}

function project($path) {
    if (isset($path[0])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $path[0]);
        if (empty($project)) {
            error('Missing project');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project');
        }
    } else {
        error('No such project');
    }
    
    echo shell_exec('__PHP__ __EXAKAT__ queue -p '.$project);
    echo json_encode(array('project' => $project));
}

function onepage($path) {
    if (isset($path[0])) {
        $file = preg_replace('/[^a-zA-Z0-9-_]/', '', $path[0]);
        if (empty($file)) {
            error('Missing file');
        }

        if (!file_exists(__DIR__.'/onepage/code/'.$file.'.php')) {
            error('No such file');
        }
    } else {
        error('No such file');
    }

    if (!file_exists(__DIR__.'/onepage/reports/'.$file.'.json')) {
        error('No such results');
    }
    
    readfile(__DIR__.'/onepage/reports/'.$file.'.json');
}

function report($path) {
    if (isset($path[0])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $path[0]);
        if (empty($project)) {
            error('Missing project');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project');
        }
    } else {
        error('No such project');
    }
    
    // Check on report, then get dump.sqlite.
    if (!file_exists(__DIR__.'/'.$project.'/report')) {
        error('No report available');
    }
    
    readfile(__DIR__.'/'.$project.'/dump.sqlite');
}

function status($path) {
    global $initTime;
    
    if (isset($path[0]) && !empty($path[0])) {
        if (file_exists(__DIR__.'/'.$path[0].'/')) {
            $json = shell_exec('__PHP__ __EXAKAT__ status -p '.$path[0].' -json');
            echo $json;
        } else {
            error('No such project');
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
    fwrite($fp, "$id\n");
    fclose($fp);
}

function autoProjectName() {
    $files = glob(__DIR__.'/*');
    return 'a'.count($files);
}

function autoOnagepageName() {
    $files = glob(__DIR__.'/onepage/code/*');
    return 'o'.count($files);
}

function error($message) {
    echo json_encode(array('error' => $message));
    exit;
}

function serverLog($message) {
    $fp = fopen(__DIR__.'/server.log', 'a');
    fwrite($fp, date('r')."\t$message\n");
    fclose($fp);
}

?>