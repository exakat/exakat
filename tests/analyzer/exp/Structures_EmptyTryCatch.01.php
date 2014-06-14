<?php

$expected     = array('try { /**/ } catch (Exception $e11) { /**/ } ',
                      'try { /**/ } catch (Exception $e13) { /**/ }  catch (Exception $e12) { /**/ }  catch (Exception $e1) { /**/ } ',
                      'try { /**/ } catch (Exception $e3) { /**/ }  catch (Exception $e2) { /**/ }  catch (Exception $e) { /**/ } ',
);

$expected_not = array('try { /**/ } catch (Exception $f11) { /**/ } ',);

?>