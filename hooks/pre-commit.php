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

use League\CLImate\CLImate;
use StanislavPivovartsev\ReviewBundle\Checker\PhpCodeShifferChecker;
use StanislavPivovartsev\ReviewBundle\Fixer\PhpCsFixer;
use StaticReview\Issue\Issue;
use StaticReview\Reporter\Reporter;
use StaticReview\StaticReview;
use StaticReview\VersionControl\GitVersionControl;
use Symfony\Component\Process\Process;

$reporter = new Reporter();
$review = new StaticReview($reporter);

// Add any reviews to the StaticReview instance, supports a fluent interface.
$review->addReview(new PhpCsFixer())
       ->addReview(new PhpCodeShifferChecker());

$git = new GitVersionControl();

// Review the staged files.
$review->files($git->getStagedFiles());

echo PHP_EOL;

$climate = new CLImate();

if ($reporter->hasIssues()) {
    foreach ($reporter->getIssues() as $issue) {
        /** @var Issue $issue */
        if ($issue->getLevel() == Issue::LEVEL_WARNING) {
            (new Process('git add '.$issue->getSubject()->getName()))->run();
        }

        if ($issue->getLevel() == Issue::LEVEL_ERROR) {
            $climate->backgroundRed($issue->getMessage());
        }
    }
    $climate->error('Review has found errors');
}

// Check if any issues were found.
// Exit with a non-zero to block the commit.
($reporter->hasIssues()) ? exit(1) : exit(0);
