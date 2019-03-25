<?php

XMLWriter::startDTD();
other::startDTD();
XMLWriter::startDTD_unknown();
XMLWriter::startDTD(1); // 1 to 3 ar OK
XMLWriter::startDTD(1, 2);
XMLWriter::startDTD(1, 2, 3);
XMLWriter::startDTD(1, 2, 3, 4);
XMLWriter::startDTD(1, 2, 3, 4, 5);

other::startDTD(1); 
other::startDTD(1, 2);
other::startDTD(1, 2, 3);
other::startDTD(1, 2, 3, 4);
other::startDTD(1, 2, 3, 4, 5);

$XMLWriter = new XMLWriter();
$XMLWriter->startDTD();
$other->startDTD();
$XMLWriter->startDTD_unknown();
$XMLWriter->startDTD(1);
$XMLWriter->startDTD(1, 2);
$XMLWriter->startDTD(1, 2, 3);
$XMLWriter->startDTD(1, 2, 3, 4);
$XMLWriter->startDTD(1, 2, 3, 4, 5);

// Functioncall : ignored
startdtd();
startdtd(1, 2);
startdtd(1, 2, 3, 4, 5);

?>