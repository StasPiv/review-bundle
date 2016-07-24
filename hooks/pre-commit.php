#!/usr/bin/env php
<?php

/*
 * This file is part of StaticReview
 *
 * Copyright (c) 2014 Samuel Parkinson <@samparkinson_>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @see http://github.com/sjparkinson/static-review/blob/master/LICENSE
 */

$included = include file_exists(__DIR__.'/../vendor/autoload.php')
    ? __DIR__.'/../vendor/autoload.php'
    : __DIR__.'/../../../autoload.php';

if (!$included) {
    echo 'You must set up the project dependencies, run the following commands:'.PHP_EOL
       .'curl -sS https://getcomposer.org/installer | php'.PHP_EOL
       .'php composer.phar install'.PHP_EOL;

    exit(1);
}

use StasPiv\Review\Handler\ReviewHandler;

(new ReviewHandler())->run();
