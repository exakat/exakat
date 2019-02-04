<?php
\pcov\start();
$d = [];
for ($i = 0; $i < 10; $i++) {
	$d[] = $i * 42;
}
\pcov\stop();
\pcov\stap();
var_dump(\pcov\collect());
?>