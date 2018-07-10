<?php

    function a( $content ) {
        return preg_replace_callback(
            '/a/',
            function ( $matches ) use ( $styleMapping )
            {
                $color = 3;
            },
            $content
        );
    }

    function b( $content ) {
        return $c = preg_replace_callback(
            '/a/',
            function ( $matches ) use ( $styleMapping )
            {
                $color = 3;
            },
            $content
        );
    }

?>