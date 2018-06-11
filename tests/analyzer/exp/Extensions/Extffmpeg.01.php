<?php

$expected     = array('ffmpeg_movie(dirname(__FILE__) . \'/test_media/robot.avi\')',
                     );

$expected_not = array('A\\B\\C\\ffmpeg_movie("namespaced")',
                     );

?>