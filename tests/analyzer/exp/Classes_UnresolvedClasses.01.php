<?php

$expected     = array('ba( )',  // namespace a

                      'ab( )',   // namespace b

                      'ab( )',   // namespace c
                      'ba( )', 

                      'f\\ab( )', // namespace d12
                      'ba( )',
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