<?php
// Open fdf from input string provided by the extension
// The pdf form contained several input text fields with the names
// volume, date, comment, publisher, preparer, and two checkboxes
// show_publisher and show_preparer.
$fdf = fdf_open_string($HTTP_FDF_DATA);
$volume = fdf_get_value($fdf, "volume");
echo "The volume field has the value '<b>$volume</b>'<br />";

$date = fdf_get_value($fdf, "date");
echo "The date field has the value '<b>$date</b>'<br />";

$comment = fdf_get_value($fdf, "comment");
echo "The comment field has the value '<b>$comment</b>'<br />";

if (fdf_get_value($fdf, "show_publisher") == "On") {
  $publisher = fdf_get_value($fdf, "publisher");
  echo "The publisher field has the value '<b>$publisher</b>'<br />";
} else
  echo "Publisher shall not be shown.<br />";

if (fdf_get_value($fdf, "show_preparer") == "On") {
  $preparer = fdf_get_value($fdf, "preparer");
  echo "The preparer field has the value '<b>$preparer</b>'<br />";
} else
  echo "Preparer shall not be shown.<br />";
fdf_close($fdf);

$fdf = new fdf("name");

?>