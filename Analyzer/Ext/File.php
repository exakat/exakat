<?php
/*
   +----------------------------------------------------------------------+
   | Cornac, PHP code inventory                                           |
   +----------------------------------------------------------------------+
   | Copyright (c) 2010 - 2011                                            |
   +----------------------------------------------------------------------+
   | This source file is subject to version 3.01 of the PHP license,      |
   | that is bundled with this package in the file LICENSE, and is        |
   | available through the world-wide-web at the following url:           |
   | http://www.php.net/license/3_01.txt                                  |
   | If you did not receive a copy of the PHP license and are unable to   |
   | obtain it through the world-wide-web, please send a note to          |
   | license@php.net so we can mail you a copy immediately.               |
   +----------------------------------------------------------------------+
   | Author: Damien Seguy <damien.seguy@gmail.com>                        |
   +----------------------------------------------------------------------+
 */

class Cornac_Auditeur_Analyzer_Ext_File extends Cornac_Auditeur_Analyzer_Functioncalls {
	protected	$title = 'Files functions';
	protected	$description = 'Native PHP function to handle files, being used.';

	
	public function analyse() {
	    $this->functions = array(
'basename',
'chgrp',
'chmod',
'chown',
'clearstatcache',
'copy',
'delete',
'dirname',
'disk_free_space',
'disk_total_space',
'diskfreespace',
'fclose',
'feof',
'fflush',
'fgetc',
'fgetcsv',
'fgets',
'fgetss',
'file_exists',
'file_get_contents',
'file_put_contents',
'file',
'fileatime',
'filectime',
'filegroup',
'fileinode',
'filemtime',
'fileowner',
'fileperms',
'filesize',
'filetype',
'flock',
'fnmatch',
'fopen',
'fpassthru',
'fputcsv',
'fputs',
'fread',
'fscanf',
'fseek',
'fstat',
'ftell',
'ftruncate',
'fwrite',
'glob',
'is_dir',
'is_executable',
'is_file',
'is_link',
'is_readable',
'is_uploaded_file',
'is_writable',
'is_writeable',
'lchgrp',
'lchown',
'link',
'linkinfo',
'lstat',
'mkdir',
'move_uploaded_file',
'parse_ini_file',
'parse_ini_string',
'pathinfo',
'pclose',
'popen',
'readfile',
'readlink',
'realpath_cache_get',
'realpath_cache_size',
'realpath',
'rename',
'rewind',
'rmdir',
'set_file_buffer',
'stat',
'symlink',
'tempnam',
'tmpfile',
'touch',
'umask',
'unlink',
);
	    parent::analyse();
	}
}

?>