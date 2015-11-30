<?php

/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2014, Maciej Szkamruk <ex3v@ex3v.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * ProgressBar - class that helps you display pretty progress bar with 
 * ETA while executing php scripts in command line.
 * 
 * To use, simply initialize it before loop, providing amount of steps in constructor:
 * <code>
 * $progressBar = new ProgressBar(300);
 * </code>
 * 
 * While in loop, call below command to display current progress:
 * 
 * <code>
 * foreach(...){
 *      echo $progressBar->drawCurrentProgress();
 * }
 * </code>
 *
 * Any modifications are warmly welcome. Especially modifications 
 * that will make calculating ETA more adaptive - for now it works 
 * best for loops with more or less equal time between steps.
 * 
 * Have fun!
 * 
 * @author Maciej Szkamruk <ex3v@ex3v.com>
 */
class ProgressBar {

    private $currentProgress;
    private $endProgress;
    private $currentTime;

    /**
     * Constructor. Requires <strong>$endProgress</strong> param. 
     * This param should be Integer value indicating, how many iterations 
     * will loop, that this progress bar is used in, contain.
     * 
     * @param integer $endProgress end progress
     * @throws InvalidArgumentException
     */
    public function __construct($endProgress)
    {
        if (!is_numeric($endProgress) || $endProgress < 1) {
            throw new InvalidArgumentException('Provided end progress value should be numeric.');
        }
        $this->endProgress = $endProgress;
        $this->currentTime = microtime(true);
    }

    /**
     * Returns current progress. <strong>$currentProgress</strong> 
     * parameter is optional. If not provided, current progress 
     * will be incremented by one.
     * 
     * @param int $currentProgress
     * @return string
     * @throws InvalidArgumentException
     */
    public function drawCurrentProgress($currentProgress = null)
    {
        if ($currentProgress !== null) {
            if ($currentProgress < $this->currentProgress) {
                throw new InvalidArgumentException("Provided current progress is smaller than previous one.");
            } else {
                $this->currentProgress = $currentProgress;
            }
        } else {
            $this->currentProgress++;
        }

        $progress = $this->currentPercentage();
        $maxWidth = $this->getTerminalWidth();
        $etaNum = $this->getETA($progress);

        return $this->buildBar($progress, $maxWidth, $etaNum);
    }

    /**
     * Calculates current percentage
     * @return int
     */
    private function currentPercentage()
    {
        $progress = $this->currentProgress / $this->endProgress;

        return $progress * 100;
    }

    /**
     * Builds progress bar row using provided data
     * 
     * @param int $progress
     * @param int $maxWidth
     * @param string $etaNum
     * @return string
     */
    private function buildBar($progress, $maxWidth, $etaNum)
    {
        $eta = $etaNum ? '(ETA: ' . $etaNum . ')' : '';
        $percentage = number_format($progress, 2) . "%";

        $widthLeft = $maxWidth - 1 - strlen($eta) - 1 - strlen($percentage) - 2;


        $prgDone = ceil($widthLeft * ($progress / 100));
        $prgNotDone = $widthLeft - $prgDone;

        $out = "[" . str_repeat("=", $prgDone) . str_repeat(" ", $prgNotDone) . '] ' . $percentage . ' ' . $eta;

        return "\r" . $out;
    }

    /**
     * Returns terminal width
     * 
     * @return int
     */
    private function getTerminalWidth()
    {
        return exec('tput cols');
    }

    /**
     * Calculates and returns ETA with human timing formatting
     * 
     * @param int $progress
     * @return string
     */
    private function getETA($progress)
    {


        $currTime = microtime(true);

        if (!$progress || $progress <= 0 || $progress === false) {
            return "";
        }

        try {
            $etaTime = (($currTime - $this->currentTime) / $progress) * (100 - $progress);

            $diff = ceil($etaTime);

            $eta = $this->humanTiming($diff);
        } catch (Exception $ex) {
            $eta = '';
        }

        return $eta;
    }

    /**
     * Converts numeric time to human-readable format
     * 
     * @param int $time
     * @return string
     */
    private function humanTiming($time)
    {

        $tokens = array(
            31536000 => 'y',
            2592000 => 'mo',
            604800 => 'w',
            86400 => 'd',
            3600 => 'h',
            60 => 'm',
            1 => 's'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) {
                continue;
            }
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits . '' . $text;
        }
    }

}