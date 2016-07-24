<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 24.07.16
 * Time: 22:12.
 */

namespace StasPiv\Review;

use Symfony\Component\Process\Process;

trait ProcessAwareTrait
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * @param string $cmd
     *
     * @return Process
     */
    public function getProcess(string $cmd) : Process
    {
        if (isset($this->process)) {
            return $this->process;
        }

        return $this->process = $this->createProcess($cmd);
    }

    /**
     * @param string $cmd
     *
     * @return Process
     */
    public function createProcess(string $cmd) : Process
    {
        return new Process($cmd);
    }
}
