<?php

$expected     = array('$aspublicButSBProtectedSelf',    // a
                      '$aspublicButSBProtectedStatic',
                      '$aspublicButSBProtectedFull',
                      '$apublicButSBProtected',
                                                    
                      '$aspublicButSBProtectedSelf',    // ab
                      '$aspublicButSBProtectedStatic',
                      '$aspublicButSBProtectedFull',
                      '$apublicButSBProtected',
                      '$aspublicButReally', 
                      '$aspublicButReally2',
                      );

$expected_not = array('$asprotected',
                      '$asprivate',
                      '$aspublicButReally', 
                      '$aspublicButReally2',
                      
                      '$aprotected',
                      '$aprivate',
                      '$apublicButReally', 
                      '$apublicButReally2'
                      );
?>