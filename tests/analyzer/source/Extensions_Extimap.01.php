<?php
	$mbox = @imap_open($server, $user, $pass);
	$msgno = imap_msgno($mbox, $mess);
	$struct = imap_fetchstructure($mbox, $msgno);
	$file = imap_fetchbody($mbox, $msgno, $part);
?>