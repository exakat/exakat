<?php
$domainObject = new COM("WinNT://Domain");
while ($obj = $domainObject->Next()) {
   echo $obj->Name . "<br />";
}
?>