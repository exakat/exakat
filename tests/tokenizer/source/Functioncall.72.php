<?php

        if (!B(C::$a)) return;

        C::$a = E(
            include __DIR__ .  'F',
            include __DIR__ .  'G',
            include __DIR__ .  'H',
            include __DIR__ .  'I'
        );
