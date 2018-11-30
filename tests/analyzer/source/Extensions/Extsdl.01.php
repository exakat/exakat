<?php

    // Shamelessly taken from Benoit Viguier
    //https://github.com/b-viguier/PhpOkoban/blob/master/src/SdlRenderer.php
        \SDL_Init(SDL_INIT_VIDEO);
        $this->window = \SDL_CreateWindow(
            $title,
            SDL_WINDOWPOS_UNDEFINED, SDL_WINDOWPOS_UNDEFINED,
            $this->width = $width, $this->height = $height,
            SDL_WINDOW_SHOWN
        );
        $renderer = \SDL_CreateRenderer($a->window, -1, 0);
        
        
?>