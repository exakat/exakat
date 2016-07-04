<?php

$expected     = array('bax( )',  // namespace a

                      'abx( )',   // namespace b

                      'abx( )',   // namespace c
                      'bax( )', 

                      'f\\abx( )', // namespace d12
                      'bax( )',
/*
                      'a\\ab( )', // namespace d1
                      'f\\ab( )',
                      'ba( )',

                      'e\\ab( )', // namespace d1
                      'f\\ab( )',
                      'ba( )',

                      'e\\ab( )', // namespace d
                      'a\\ab( )',
                      'f\\ab( )',
                      'ba( )'
                      */
                      );

$expected_not = array();

?>