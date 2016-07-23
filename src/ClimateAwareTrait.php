<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 23.07.16
 * Time: 15:05.
 */

namespace StasPiv\Service;

use League\CLImate\CLImate;

trait ClimateAwareTrait
{
    /**
     * @var CLImate
     */
    protected $climate;

    /**
     * @return CLImate
     */
    public function getClimate()
    {
        if (isset($this->climate)) {
            return $this->climate;
        }

        return $this->climate = new CLImate();
    }
}
