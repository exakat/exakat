<?php

if (($handle = fopen("people.csv", "r")) !== FALSE) {
    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
         $data[] = $row;
    }
    fclose($handle);
}
 
// Sort by last name:
usort($data, function ($left, $right) {
     return $left[1] <=> $right[1];
});

?>