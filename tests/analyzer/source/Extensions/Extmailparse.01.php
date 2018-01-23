<?php

$fp = fopen('somemail.eml', 'r');
echo 'Best encoding: ' . mailparse_determine_best_xfer_encoding($fp);
echo 'Then ' . $mail->mailparse_determine_best_xfer_encoding($fp2);

?>