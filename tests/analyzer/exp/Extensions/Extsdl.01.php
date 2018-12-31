<?php

$expected     = array('SDL_INIT_VIDEO',
                      '\\SDL_Init(SDL_INIT_VIDEO)',
                      '\\SDL_CreateWindow($title, SDL_WINDOWPOS_UNDEFINED, SDL_WINDOWPOS_UNDEFINED, $this->width = $width, $this->height = $height, SDL_WINDOW_SHOWN)',
                      '\\SDL_CreateRenderer($a->window, -1, 0)',
                     );

$expected_not = array('$a->window',
                     );

?>