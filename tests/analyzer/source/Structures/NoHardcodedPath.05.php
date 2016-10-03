<?php

// has a protocol as first part 
fopen('jackalope://' . $token . '@' . $this->session->getRegistryKey( ) . ':' . $i . $this->path, 'rwb+');
fopen("jackalope://$asd.txt", 'rwb+');
fopen('jackalope://asd.txt', 'rwb+');

// This is not accepted, even with protocol
fopen('file://tmp/temp.file.txt', 'rwb+');
fopen('ogg://some/file.ogg', 'rwb+');
fopen("phar://some/archive.phar", 'rwb+');

fopen('http/host2.com', 'rwb+');
fopen('/http/host3.com', 'rwb+');
fopen('ftp'.$a.'/host2.com', 'rwb+');
fopen('/ftp/'.$b.'host3.com', 'rwb+');
fopen("ssh2/$host2.com", 'rwb+');
fopen("/ssh2/$host4.com", 'rwb+');

// This is acceptable, as remote source of data may change without 
fopen('php://memory', 'rwb+');
fopen('http://'.HOST.'.com', 'rwb+');
fopen('https://host.com', 'rwb+');
fopen("ftp://$host.com", 'rwb+');
fopen('ssh2://host.com', 'rwb+');

// this is accepted as dynamic
fopen('file://' . $token . '@' . $this->session->getRegistryKey( ) . ':' . $i . $this->path, 'rwb+');

?>