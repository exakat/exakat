<?php

$expected     = array('fdf_open_string($HTTP_FDF_DATA)',
                      'fdf_get_value($fdf, "volume")',
                      'fdf_get_value($fdf, "date")',
                      'fdf_get_value($fdf, "comment")',
                      'fdf_get_value($fdf, "show_publisher")',
                      'fdf_get_value($fdf, "publisher")',
                      'fdf_get_value($fdf, "show_preparer")',
                      'fdf_get_value($fdf, "preparer")',
                      'fdf_close($fdf)',
                     );

$expected_not = array('new fdf("name")',
                     );

?>