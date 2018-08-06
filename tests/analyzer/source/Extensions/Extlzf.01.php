<?php
$compressed = lzf_compress("This is test of LZF extension");

echo base64_encode($compressed);

// This is not the actual method
$uncompressed = lzf_uncompress($compressed);
?>
