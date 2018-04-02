<?php
if (!xcache_isset("count")) {
  xcache_set("count", load_count_from_mysql());
}
?>
This guest book has been visited <?php echo $count = xcache_inc("count"); ?> times.
<?php
// save every 100 hits
if (($count % 100) == 0) {
  save_count_to_mysql($count);
}

echo XCACHE_NOT_A_CONSTANT;
?>