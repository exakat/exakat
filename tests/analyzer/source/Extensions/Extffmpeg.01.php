<?php
$mov = new ffmpeg_movie(dirname(__FILE__) . '/test_media/robot.avi');
$mov = new A\B\C\ffmpeg_movie("namespaced");

/* move frame point to frame 5 */
$mov->getFrame(5);
printf("ffmpeg getFrameNumber(): %d\n", $mov->getFrameNumber());
?>
