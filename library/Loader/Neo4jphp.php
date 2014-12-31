<?php

namespace Loader;

class Neo4jphp extends Client {
    static public function save_chunk() { }

    static public function finalize() {
        shell_exec('php bin/build_root');
    }
}

?>
