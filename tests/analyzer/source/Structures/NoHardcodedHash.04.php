<?php

// Common 
$ignore[] = "VARIABLE";
$ignore[] = "REQUIRED";
$ignore[] = "TABINDEX";
$ignore[] = "TEXTEDIT";
$ignore[] = "AVOGADRO";
$ignore[] = "ALLOCATE";
$ignore[] = "UNHIDABLECOLUMNS";

// dates
$ignore[] = '20150511';
$ignore[] = '19150512';

//not dates
$ignore[] = '20157511'; // No such month
$ignore[] = '20150512201502'; // Wrong size

// OK
$a = 'abcdef12';
$a = 'ABCDEF12';

?>