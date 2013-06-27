<?php

if($operation == 'import') {

	if(!submitcheck('importsubmit', 1)) {
		$exportlog = $exportsize = $exportziplog = array();
		check_exportfile($exportlog, $exportziplog, $exportsize);
		if(empty($exportlog) && empty($exportziplog)) {
			show_msg('backup_file_unexist');
		}

		show_importfile_list($exportlog, $exportziplog, $exportsize);

	} else {

		$readerror = 0;
		$datafile_vol1 = trim(getgpc('datafile_vol1'));
		if($datafile_vol1) {
			$datafile = $datafile_vol1;
		} else {
			$datafile = getgpc('datafile_server', 'G');
		}
		$datafile = urldecode($datafile);
		if(@$fp = fopen($datafile, 'rb')) {
			$confirm = trim(getgpc('confirm', 'G'));
			$confirm = $confirm ? 1 : 0;
			$sqldump = fgets($fp, 256);
			$identify = explode(',', base64_decode(preg_replace("/^# Identify:\s*(\w+).*/s", "\\1", $sqldump)));
			$dumpinfo = array('method' => $identify[3], 'volume' => intval($identify[4]), 'tablepre' => $identify[5], 'dbcharset' => $identify[6]);
			if(!$confirm) {
				$showmsg = '';
				if($dumpinfo['tablepre'] != $_config['db']['1']['tablepre'] && !getgpc('ignore_tablepre', 'G')) {
					$showmsg .= lang('tableprediff');
				}
				if($dumpinfo['dbcharset'] != $_config['db']['1']['dbcharset']) {
					$showmsg .= lang('dbcharsetdiff');
				}
				if($showmsg) {
					show_msg(lang('different_dbcharset_tablepre', TRUE, array('diff' => $showmsg)), $siteurl.'restore.php?operation=import&datafile_server='.$datafile.'&autoimport=yes&importsubmit=yes&confirm=yes', 'confirm');
				}
			}

			if($dumpinfo['method'] == 'multivol') {
				$sqldump .= fread($fp, filesize($datafile));
			}
			fclose($fp);
		} else {
			if(getgpc('autoimport', 'G')) {
				touch($lock_file);
				show_msg('database_import_multivol_succeed', '', 'message', 1);
			} else {
				show_msg('database_import_file_illegal');
			}
		}

		if($dumpinfo['method'] == 'multivol') {
			$sqlquery = splitsql($sqldump);
			unset($sqldump);

			foreach($sqlquery as $sql) {

				$sql = syntablestruct(trim($sql), $db->version() > '4.1', DBCHARSET);

				if($sql != '') {
					$db->query($sql, 'SILENT');
					if(($sqlerror = $db->error()) && $db->errno() != 1062) {
						$db->halt('MySQL Query Error', $sql);
					}
				}
			}

			$delunzip = getgpc('delunzip', 'G');
			if($delunzip) {
				@unlink($datafile);
			}

			$datafile_next = preg_replace("/-($dumpinfo[volume])(\..+)$/", "-".($dumpinfo['volume'] + 1)."\\2", $datafile);
			$datafile_next = urlencode($datafile_next);
			if($dumpinfo['volume'] == 1) {
				show_msg(lang('database_import_multivol_redirect', TRUE, array('volume' => $dumpinfo['volume'])),
					$siteurl."restore.php?operation=import&datafile_server=$datafile_next&autoimport=yes&importsubmit=yes&confirm=yes".(!empty($delunzip) ? '&delunzip=yes' : ''),
					'redirect');
			} elseif(getgpc('autoimport', 'G')) {
				show_msg(lang('database_import_multivol_redirect', TRUE, array('volume' => $dumpinfo['volume'])), $siteurl."restore.php?operation=import&datafile_server=$datafile_next&autoimport=yes&importsubmit=yes&confirm=yes".(!empty($delunzip) ? '&delunzip=yes' : ''), 'redirect');
			} else {
				show_msg('database_import_succeed', '', 'message', 1);
			}
		} elseif($dumpinfo['method'] == 'shell') {

			list($dbhost, $dbport) = explode(':', $dbhost);
			$query = $db->query("SHOW VARIABLES LIKE 'basedir'");
			list(, $mysql_base) = $db->fetch($query, MYSQL_NUM);

			$mysqlbin = $mysql_base == '/' ? '' : addslashes($mysql_base).'bin/';
			shell_exec($mysqlbin.'mysql -h"'.$dbhost.($dbport ? (is_numeric($dbport) ? ' -P'.$dbport : ' -S"'.$dbport.'"') : '').
				'" -u"'.$dbuser.'" -p"'.$dbpw.'" "'.$dbname.'" < '.getgpc('datafile'));

			show_msg('database_import_succeed', '', 'message', 1);
		} else {
			show_msg('database_import_format_illegal');
		}
	}

} 