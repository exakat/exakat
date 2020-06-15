<?php

trait a {
    public $c;
    /**
     * @var 3
     */
    public $d;

    function __construct($a = '', $b = false)
    {
        /**
         * @var 4
         */
        global $request, $b;

        /**
         * @var 5
         */
        static $request, $b;
    }

}
