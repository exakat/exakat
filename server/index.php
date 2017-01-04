<?php

const PIPEFILE = '/tmp/onepageQueue';

$initTime = time(true);

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$orders = array('stop', 'report', 'onepage', 'archive', 'project', 'status');
$offset = strpos($path, '/', 1);
if ($offset === false) {
    exit;
}

$command = substr($path, 1, $offset - 1);
if (!in_array($command, $orders)) {
    exit;
}


$command($path);
otherwise($path);

/// Function definitions

function archive($path) {
    list(,,$project, $type, $compression) = explode('/', $path.'/');
    if (empty($compression)) {
        $compression = 'zip';
    }
    
    $types = array('html'     => 'report',
                   'faceted'  => 'faceted',
                   'faceted2' => 'faceted2',
                   'text'     => 'report.txt',
                   'json'     => 'report.json');
                  
    if (!isset($types[$type])) {
        print "No such format as '".htmlentities($type, ENT_COMPAT | ENT_HTML401 , 'UTF-8')."'\n";
        return;
    }
    
    if (file_exists(__DIR__.'/../progress')) {
        $status = json_decode(file_get_contents(__DIR__.'/../progress/jobqueue.exakat'));
        if (substr($status->job, 0, strlen($project)) == $project && $status->progress < 100) {
            print "No such report as '".htmlentities($type, ENT_COMPAT | ENT_HTML401 , 'UTF-8')."'\n";
            return;
        }
    }
    $report = __DIR__.'/../projects/'.$project.'/'.$types[$type];
    if (!file_exists($report)) {
        return;
    }
    
    $archive = './projects/'.$project.'/'.$types[$type].'.'.$compression;

    if (!file_exists($archive)) {
        shell_exec('cd '.__DIR__.'/'.$project.'; zip -r '.$types[$type].'.zip '.$types[$type]);
    }

    header("Content-Type: ".getMimeContentType($archive));
    header('Content-Disposition: attachment; filename="downloaded.'.'zip'.'"');
   
    readfile($archive);
}

function onepage($path) {
    if (isset($_REQUEST['id'])) {
        if (file_exists('./out/'.$_REQUEST['id'].'.json')) {
            header('Content-Type: application/json');
            readfile('./out/'.$_REQUEST['id'].'.json');
        } else {
            $json = json_decode(file_get_contents('./progress/jobqueue.exakat'));
            if ($json->job == $_REQUEST['id']) {
                echo json_encode(array('status' => $json->progress));
            } else {
                echo json_encode(array('status' => 0));
            }
        }
    } elseif (isset($_REQUEST['script'])) {
        $file = './in/'.md5($_REQUEST['script']).'.php';
        file_put_contents($file, $_REQUEST['script']);

        pushToQueue(md5($_REQUEST['script']));
        echo json_encode(array('id' => md5($_REQUEST['script'])));
    } 
}

function project($path) {
    if (isset($_REQUEST['project'])) {
        // Validation
        if (!file_exists('./projects/'.$_REQUEST['project'])) {
            echo json_encode(array('project'  => $_REQUEST['project'], 
                                   'progress' => 0, 
                                   'status'   => 'Not found')); // empty array
            return;
        } elseif (!file_exists('./projects/'.$_REQUEST['project'].'/datastore.sqlite')) {
            echo json_encode(array('project'  => $_REQUEST['project'], 
                                   'progress' => 0, 
                                   'status'   => 'Initialization'));
            return;
        } else {
            $return = array('project' => $_REQUEST['project']);

            $sqlite = new \Sqlite3('./projects/'.$_REQUEST['project'].'/datastore.sqlite');
            $res = $sqlite->query('SELECT value FROM hash WHERE key="tokens"');
            $row = $res->fetchArray();
            $return['size'] = $row[0];
            
            if (file_exists('./projects/'.$_REQUEST['project'].'/report')) {
                $return['report'] = true;
                if (!file_exists('./projects/'.escapeshellarg($_REQUEST['project']).'/report')) {
                    shell_exec('cd ./projects/'.escapeshellarg($_REQUEST['project']).'; zip -r report.zip report > /dev/null 2>/dev/null &');
                    $return['zip'] = false;
                    $return['status'] = 'Archiving';
                } else {
                    $return['zip'] = true;
                    // Not status anymore
                }
            } else {
                $return['status'] = 'Running';
            }

            echo json_encode($return);
        }
    } elseif (isset($_REQUEST['vcs'])) {
        $project = 'a'.substr(md5($_REQUEST['vcs']), 0, 8);
        
        if (file_exists('./projects/'.$project)) {
            echo json_encode(array('project' => $project));
            return;
        }

        shell_exec('__PHP__ __EXAKAT__ init -p '.$project.' -R '.escapeshellarg($_REQUEST['vcs']));
        
        pushToQueue($project);
        echo json_encode(array('project' => $project));
    }
}

function report($path) {
    list(,,$project, $type, $r) = explode('/', $path.'/');

    // forgot the final /
    if (empty($r) && substr($path, -1) !== '/') {
        header("Location: /report/$project/$type/");
        return;
    }

    $r = substr($path, strlen('/report/') + strlen($project) + 1 + strlen($type) + 1);

    $types = array('html'     => 'report',
                   'faceted'  => 'faceted',
                   'faceted2' => 'faceted2',
                   'text'     => 'report.txt',
                   'json'     => 'report.json');
    if (!isset($types[$type])) {
        print "No such report as '".htmlentities($type, ENT_COMPAT | ENT_HTML401 , 'UTF-8')."'\n";
        return;
    }

    if (file_exists(__DIR__.'/'.$project.'/'.$types[$type])) {
        if (in_array($type, array('html', 'faceted', 'faceted2')) && empty($r)) {
            $r = '/index.html';
        } else {
            $r = '/'.$r;
        }

        header("Content-Type: ".getMimeContentType(__DIR__.'/'.$project.'/'.$types[$type].$r));

        readfile(__DIR__.'/'.$project.'/'.$types[$type].$r);
        return;
    } else {
        echo "The '$type' report hasn't been generated yet.";
        return;
    }
}

function status($path) {
    global $initTime;
    
    $d = explode('/', $path);
    if (isset($d[2]) && !empty($d[2]) && file_exists(__DIR__.'/'.$d[2].'/')) {
        $json = shell_exec('__PHP__ __EXAKAT__ status -p '.$d[2].' -json');
        echo $json;
    } else {
        $status = array(
            'Status'       => 'OK',
            'Running Time' => duration(time() - $initTime),
            'Init Time '   => date('r', $initTime),
            'Queue'        => file_exists(PIPEFILE) ? 'Yes' : 'No'
        );
        echo json_encode($status);
    }
}

function stop($path) {
    echo "<p>Shutting down server</p>";
    ob_flush();

    unlink('./projects/index.php');

    exec('kill '.getmypid());
    // This is killed.
}

function otherwise($path) {
    echo "<p>$path (otherwise)</p>";
    return;
}

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

function getMimeContentType($path) {
    static $mimeTypes = array('css'  => 'text/css',
                              'gif'  => 'image/gif',
                              'png'  => 'image/png',
                              'woff' => 'application/x-font-woff',
                              'js'   => 'text/javascript',
                              'html' => 'text/html',
                              'zip'  => 'application/octet-stream',
                              );
    
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    
    if (!isset($mimeTypes[$ext])) {
        print "missing '$ext' ($path) mime type\n";
        return '';
    }
    
    return $mimeTypes[$ext];
}

?>