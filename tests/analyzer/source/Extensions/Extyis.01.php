<?php
$entry = yp_next($domain, "passwd.byname", "joe");

if (!$entry) {
    echo "No more entries found\n";
    echo "<!--" . yp_errno() . ": " . yp_err_string() . "-->";
    echo yp_errmsg();
}

$key = key($entry);

echo "The next entry after joe has key " . $key
      . " and value " . $entry[$key];
?>