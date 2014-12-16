<?php

namespace Loader;

class Neo4jphp extends Client {
    static function save_chunk() {
    }

    static function finalize() {
        shell_exec('php bin/build_root');
    }
}

?>
