<?php
/* Create new image object */
$image = new ZBarCodeImage("test.jpg");

/* Create a barcode scanner */
$scanner = new ZBarCodeScanner();

/* Scan the image */
$barcode = $scanner->scan($image);

/* Loop through possible barcodes */
if (!empty($barcode)) {
    foreach ($barcode as $code) {
        printf("Found type %s barcode with data %s\n", $code['type'], $code['data']);
    }
}
?>